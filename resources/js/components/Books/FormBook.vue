<template>
    <div>
        <loader-overlay v-if="loader"></loader-overlay>
        <form ref="formBook" @submit.prevent="handleSubmit">
            <div class="uk-margin">
                <label class="uk-form-label">Title</label>
                <input type="text" class="uk-form-controls uk-input" v-model="book.title" placeholder="Title of book" required>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label">Category</label>
                <select id="select-form-category" class="uk-form-controls" v-model="book.category_id">
                    <option v-for="(category, cI) in categories" :key="cI" :value="category.id">{{ category.title }}</option>
                </select>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label">Author</label>
                <select id="select-form-writer" v-model="book.writer_id" class="uk-form-controls" required></select>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label">Published Date</label>
                <input type="date" class="uk-form-controls uk-input" v-model="book.published_date" required>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label">Synopsis</label>
                <textarea class="uk-form-controls uk-textarea" rows="5" v-model="book.synopsis" required></textarea>
            </div>
            <div class="uk-margin-medium">
                <button type="submit" class="uk-button uk-button-primary uk-box-shadow-hover-large uk-text-capitalize" :disabled="loader">
                    {{ mode }} book
                </button>
            </div>
        </form>
    </div>
</template>

<script>

import { EventBus } from './../../bus'

export default {
    data() {
        return {
            mode: 'create',
            loader: false,
            categories: [],
            selectCategory: null,
            selectAuthor: null,
            book: {
                id: null,
                writer_id: null,
                category_id: null,
                published_date: null,
                synopsis: null
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
        loadBook: function (book) {
            this.loader = true
            axios.get(route('admin.books.edit', { id: book.id }))
            .then(response => {
                this.loader = false
                this.book = response.data
                this.selectCategory.set(this.book.book_category_id)
                this.selectAuthor.setData([{
                    text: book.author,
                    value: this.book.writer_id,
                }])
                this.selectAuthor.set(this.book.writer_id)
            })
            .catch(error => {
                this.loader = false
                UIkit.notification(`Error loading book: ${error}`, 'danger')
            })
        },
        handleSubmit: function () {
            if ( this.mode == 'create' ) {
                this.storeBook()
            } else {
                this.updateBook()
            }
        },
        storeBook: function () {
            this.loader = true
            axios.post(route('admin.books.store'), this.book)
            .then(book => {
                this.loader = false
                EventBus.$emit('bookCreated', book.data)
                UIkit.modal('#modal-book').hide()
                UIkit.notification('New book added', 'success')
            })
            .catch(error => {
                this.loader = false
                UIkit.notification(`Error store book: ${error}`, 'danger')
            })
        },
        updateBook: function () {
            this.loader = true
            axios.put(route('admin.books.update', { id: this.book.id }), this.book)
            .then(book => {
                this.loader = false
                EventBus.$emit('bookUpdated', book.data)
                UIkit.modal('#modal-book').hide()
                UIkit.notification('New book added', 'success')
            })
            .catch(error => {
                this.loader = false
                UIkit.notification(`Error store book: ${error}`, 'danger')
            })
        },
        clearForm: function () {
            for (let key in this.book) {
                this.book[key] = null
            }
            this.selectAuthor.set('')
            this.selectCategory.set('')
            this.$refs.formBook.reset()
        }
    },
    created() {

        this.loadCategories()

        EventBus.$on('newBook', () => {
            this.mode = 'create'
            this.clearForm()
        })

        EventBus.$on('editBook', book => {
            this.mode = 'edit'
            this.clearForm()
            this.loadBook(book)
            UIkit.modal('#modal-book').show()
        })
    },
    mounted() {

        this.selectCategory = new SlimSelect({
            select: '#select-form-category',
            placeholder: 'Select a category'
        })

        this.selectAuthor = new SlimSelect({
            select: '#select-form-writer',
            placeholder: 'Select a writer',
            searchFilter: (option, search) => {
                let words = search.toUpperCase().split(' ')
                return words.filter(word => option.text.toUpperCase().includes(word)).length > 0
            },
            ajax: function (search, callback) {
                
                if (search.length < 3) {
                    callback('Need 3 characters')
                    return
                }

                axios.get(route('writers.search', { name: search }))
                .then( response => {

                    let data = []

                    response.data.forEach(writer => {
                        data.push({
                            text: writer.name,
                            value: writer.id
                        })
                    })

                    callback(data)
                })
                .catch( error => {
                    UIkit.notification(error, 'danger')
                    callback(false)
                })
            }
        })
    }
}
</script>