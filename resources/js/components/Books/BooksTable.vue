<template>
    <div>
        <div class="uk-flex uk-flex-middle uk-flex-between">
            <div>
                <h6 v-if="paginatedBooks.total > 0" class="uk-margin-remove uk-text-muted">{{ paginatedBooks.total }} books</h6>
            </div>
            <div class="uk-flex uk-flex-middle">
                <div class="uk-margin-small-right">
                    <a role="button" uk-toggle="#modal-book" uk-icon="plus-circle" uk-tooltip="Create a new book" @click="newBook"></a>
                </div>
                <div class="uk-margin-small-right">
                    <a role="button" uk-icon="settings" uk-tooltip="Filters"></a>
                    <div class="uk-width-medium" uk-dropdown="mode: click">
                        <book-filters @filtersUpdated="filtersUpdated"></book-filters>
                    </div>
                </div>
                <div class="uk-inline uk-align-right uk-margin-remove-vertical uk-margin-remove-left">
                    <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: search"></span>
                    <input class="uk-input" type="text" v-model="querySearch" placeholder="Search books...">  
                </div>
            </div>
        </div>
        <div class="uk-margin">
            <table class="uk-table uk-table-divider uk-table-responsive">
                <thead>
                    <tr>
                        <th>
                            <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="sortBy('title')">
                                <span v-if="filters.sortBy == 'title'" :uk-icon="filters.sortType == 'asc' ? 'arrow-down' : 'arrow-up'"></span> Title
                            </a>
                        </th>
                        <th>
                            <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="sortBy('category')">
                                <span v-if="filters.sortBy == 'category'" :uk-icon="filters.sortType == 'asc' ? 'arrow-down' : 'arrow-up'"></span> Category
                            </a>
                        </th>
                        <th>
                            <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="sortBy('author')">
                                <span v-if="filters.sortBy == 'author'" :uk-icon="filters.sortType == 'asc' ? 'arrow-down' : 'arrow-up'"></span> Writer
                            </a>
                        </th>
                        <th>
                            <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="sortBy('published_date')">
                                <span v-if="filters.sortBy == 'published_date'" :uk-icon="filters.sortType == 'asc' ? 'arrow-down' : 'arrow-up'"></span> Published Date
                            </a>
                        </th>
                        <th>
                            <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="sortBy('existences')">
                                <span v-if="filters.sortBy == 'existences'" :uk-icon="filters.sortType == 'asc' ? 'arrow-down' : 'arrow-up'"></span> Existences
                            </a>
                        </th>
                        <th>
                            <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="sortBy('borrowed_books')">
                                <span v-if="filters.sortBy == 'borrowed_books'" :uk-icon="filters.sortType == 'asc' ? 'arrow-down' : 'arrow-up'"></span> Borrowed <br>Books
                            </a>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <table-body-placeholder :rows="10" :columns="7" v-if="loader"></table-body-placeholder>
                <tbody v-else>
                    <tr v-for="(book, bI) in paginatedBooks.data" :key="bI">
                        <td v-html="querySearchInAtt(book.title)"></td>
                        <td>{{ book.category }}</td>
                        <td>{{ book.author }}</td>
                        <td>{{ book.published_date }}</td>
                        <td>{{ book.existences }}</td>
                        <td>{{ book.borrowed_books }}</td>
                        <td>
                            <span v-if="deletingBooks.indexOf(book.id) >= 0" uk-spinner></span>
                            <ul v-else class="uk-iconnav">
                                <li uk-tooltip="View records">
                                    <a role="button" class="uk-preserve-width" uk-icon="album" @click="viewRecords(bI)"></a>
                                </li>
                                <li uk-tooltip="Edit information">
                                    <a role="button" class="uk-preserve-width" uk-icon="pencil" @click="editBook(bI)"></a>
                                </li>
                                <li  uk-tooltip="Delete book">
                                    <a role="button" class="uk-preserve-width" uk-icon="trash" @click="deleteBook(bI, book.id)"></a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!loader && paginatedBooks.total == 0">
                    <tr>
                        <td colspan="7"><h5>No books found</h5></td>
                    </tr>
                </tfoot>
            </table>
            <pagination :sizeData="paginatedBooks.total" :per-page="10" @updatePage="updatePage"></pagination>
        </div>
    </div>
</template>

<script>

import BookFilters from './BookFilters'
import { EventBus } from '../../bus'

export default {
    components: {
        BookFilters
    },
    data() {
        return {
            loader: true,
            page: 1,
            querySearch: '',
            paginatedBooks: {},
            promisesTyping: [],
            deletingBooks: [],
            filters: {
                category: this.category || null,
                writer: this.author || null,
                year: null,
            }
        }
    },
    watch: {
        querySearch: function (val) {
            this.promisesTyping.push(new Promise((resolve, reject) => {
                setTimeout(() => {
                    resolve()
                }, 1000)
            }))

            if (this.promisesTyping.length == 1) {
                Promise.all(this.promisesTyping).then(() => {
                    this.promisesTyping = []
                    this.filters.book = val
                    this.loadBooks()
                })
            }
        }
    },
    methods: {
        querySearchInAtt: function (title) {
            if ( this.querySearch && this.querySearch.length > 0 ) {
                const regExp = new RegExp(this.querySearch, "gi")
                return title.replace(regExp, "<b class='uk-text-primary'>$&</b>")
            }

            return title
        },
        loadBooks: function () {
            this.loader = true
            axios.get(route('admin.books', this.filters))
            .then(books => {
                this.loader = false
                this.paginatedBooks = books.data
            })
            .catch(error => {
                this.loader = false
                UIkit.notification(`Error loading books: ${error}`, 'danger')
            })
        },
        filtersUpdated: function (filters) {
            this.filters = filters
            this.filters.page = this.page
            this.loadBooks()
        },
        sortBy: function (sortBy) {
            this.filters.sortBy = sortBy
            this.filters.sortType = this.filters.sortType == 'asc' ? 'desc' : 'asc'
            this.loadBooks()
        },
        updatePage: function (page) {
            this.page = page
            this.filters.page = page
            this.loadBooks()
        },
        viewRecords: function (index) {
            EventBus.$emit('viewRecords', this.paginatedBooks.data[index])
        },
        newBook: function () {
            EventBus.$emit('newBook')
        },
        editBook: function (index) {
            EventBus.$emit('editBook', this.paginatedBooks.data[index])
        },
        deleteBook: function (index, id) {
            const self = this

            UIkit.modal.confirm(`Are you sure want delete this book? 
            This book will be removed but references to it will remain`)
            .then(function () {

                self.deletingBooks.push(id)
                
                axios.delete(route('admin.books.delete', id))
                .then(response => {
                    this.paginatedBooks.total--
                    self.paginatedBooks.data.splice(index, 1)
                    UIkit.notification('Book deleted', 'success')
                })
                .catch(error => {
                    self.deletingBooks = self.deletingBooks.filter(db => db != id)
                    UIkit.notification('Book deleted', 'danger')
                })
            }, () => {})
        }
    },
    created() {

        this.loadBooks()

        EventBus.$on('bookCreated', book => {
            book.category
            this.paginatedBooks.total++
            this.paginatedBooks.data.push(book)
        })

        EventBus.$on('bookUpdated', book => {
            const index = this.paginatedBooks.data.findIndex(b => b.id == book.id)
            if ( index >= 0 ) {
                book.existences = this.paginatedBooks.data[index].existences
                book.borrowed_books = this.paginatedBooks.data[index].borrowed_books
                this.paginatedBooks.data[index] = book
                this.$forceUpdate()
            }
        })

        EventBus.$on('recordDeleted', id => {
            const index = this.paginatedBooks.data.findIndex(r => r.id == id)
            if ( index >= 0 ) {
                this.paginatedBooks.data[index].existences -= 1
            }
        })

        EventBus.$on('recordCreated', id => {
            const index = this.paginatedBooks.data.findIndex(r => r.id == id)
            if ( index >= 0 ) {
                this.paginatedBooks.data[index].existences += 1
            }
        })
    },

}
</script>