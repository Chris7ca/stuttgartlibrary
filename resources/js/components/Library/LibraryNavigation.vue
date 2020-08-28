<template>
    <div class="uk-grid uk-margin-large" uk-grid>
        <div class="uk-width-1-5@m">
            <book-filters :author="filters.writer" :category="filters.category" @filtersUpdated="filtersUpdated"></book-filters>
        </div>
        <div class="uk-width-expand@m">
            <div>
                <div v-if="loader">
                    <p>Loading books <span class="uk-margin-small-left" uk-spinner="ratio: 0.7"></span></p>
                </div>
                <div v-if="!loader && paginatedBooks.total == 0">
                    <p>Books not found...</p>
                </div>
                <div v-show="!loader">
                    <p class="uk-text-right">{{ paginatedBooks.total }} books</p>
                    <div class="uk-grid uk-grid-match uk-child-width-1-5@xl uk-child-width-1-4@l uk-child-width-1-3@m uk-child-width-1-2@s" uk-grid>
                        <div v-for="(book, bI) in paginatedBooks.data" :key="bI">
                            <div>
                                <div class="uk-card uk-card-body uk-card-bordered uk-box-shadow-large uk-background-cover uk-height-medium uk-position-relative" :data-src="book.image" uk-img>
                                    <div v-if="book.existences == book.borrowed_books" class="uk-position-top-right uk-padding-small">
                                        <span class="uk-label uk-label-danger uk-box-shadow-medium">Not available</span>
                                    </div>
                                </div>
                                <h3 class="uk-margin-small-bottom uk-margin-small-top uk-text-bold">
                                    <a class="uk-link-reset" :href="route('books.show', book.slug)">{{ book.title }}</a>
                                </h3>
                                <a role="button" class="uk-margin-remove" @click="filters.writer = book.author_slug">{{ book.author }}</a>
                            </div>
                        </div>
                    </div>
                    <pagination :sizeData="paginatedBooks.total" :per-page="10" @updatePage="updatePage"></pagination>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Pagination from './../Pagination'
import BookFilters from './../Books/BookFilters'

export default {
    props: ['category', 'author'],
    components: {
        Pagination, BookFilters
    },
    data() {
        return {
            loader: true,
            categories: [],
            writers: [],
            page: 1,
            paginatedBooks: {},
            filters: {
                category: this.category || null,
                writer: this.author || null,
                year: null,
            }
        }
    },
    methods: {
        loadCategories: function () {
            axios.get(route('books.categories'))
            .then(categories => {
                this.categories = categories.data
            })
            .catch(error => {
                UIkit.notification(`Error loading categories: ${error}`, 'danger')
            })
        },
        loadBooks: function () {
            this.loader = true
            axios.get(route('books.list', this.filters))
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
        updatePage: function (page) {
            this.page = page
            this.filters.page = page
            this.loadBooks()
        }
    },
    created() {
        this.loadCategories()
        this.loadBooks()
    }
}
</script>