<template>
    <div>
        <form @submit.prevent="storeRecord">
            <div class="uk-margin">
                <label class="uk-form-label">Book</label>
                <select id="select-book" v-model="book.book_id" required></select>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label">ISBN</label>
                <select id="select-isbn" v-model="book.book_record_id" required></select>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label">User</label>
                <select id="select-user" v-model="book.user_id" required></select>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label">Deadline</label>
                <input type="datetime-local" class="uk-form-controls uk-input" v-model="localDate" :min="today" required>
            </div>
            <div class="uk-margin-medium">
                <button type="submit" class="uk-button uk-button-primary uk-box-shadow-hover-large uk-text-capitalize" :disabled="loader">
                    Save information <span v-if="loader" class="uk-margin-small-left" uk-spinner="ratio: 0.7"></span>
                </button>
            </div>
        </form>
    </div>
</template>

<script>

import { EventBus } from './../../bus'
import customSelect from './../../customSelect'

export default {
    data() {
        return {
            loader: false,
            selectUser: null,
            selectBook: null,
            selectISBN: null,
            localDate: '',
            book: {
                book_id: null,
                title: null,
                book_record_id: null,
                user_id: null,
                deadline: null
            }
        }
    },
    watch: {
        'book.book_id': function (id) {
            if (id) {
                this.selectISBN.enable()
                this.loadBookRecords()
            }
        },
        localDate: function (date) {
            if (date && date.includes('T')) {
                const arrayDate = date.split('T')
                this.book.deadline = arrayDate[0] + ' ' + arrayDate[1] + ':00'
            }
        },
    },
    computed: {
        today: function () {
            const now = new Date()
            return new Date(now.getTime() - now.getTimezoneOffset() * 60000)
                .toISOString()
                .substr(0, 16)
        }
    },
    methods: {
        loadBookRecords: function () {
            this.selectISBN.setData([{
                innerHTML: 'Loading information <span v-if="loader" class="uk-margin-small-left" uk-spinner="ratio: 0.7"></span>', 
                text: null, 
                value: null}
            ])
            axios.get(route('admin.books.records', this.book.book_id))
            .then(response => {
                response.data = [{isbn: '', value: ''},...response.data]
                this.selectISBN.setData(response.data.map(r => {
                    return { text: r.isbn, value: r.id }
                }))
            })
            .catch(error => {
                UIkit.notification(`Error loading records: ${error}`, 'danger')
                this.selectISBN.setData([])
            })
        },
        storeRecord: function () {
            this.loader = true
            axios.post(route('admin.books.borrows.store'), this.book)
            .then(response => {
                this.loader = false
                EventBus.$emit('borrowBookCreated')
                UIkit.modal('#moda-borrow').hide()
                UIkit.notification('Information saved', 'success')
            })
            .catch(error => {
                this.loader = false
                UIkit.notification(`Error when saving information: ${error}`, 'danger')
            })
        },
        clearForm: function () {
            this.book = {
                book_id: null,
                title: null,
                book_record_id: null,
                user_id: null,
                deadline: null
            }
        }
    },
    created () {

        EventBus.$on('newBorrow', () => {
            this.clearForm()
        })
    },
    mounted() {
        this.selectUser = customSelect.ajaxSelect('#select-user', 'Select a user', route('admin.users.find'), 'name', 'id')
        this.selectBook = customSelect.ajaxSelect('#select-book', 'Select a book', route('admin.books.find'), 'title', 'id')
        this.selectISBN = customSelect.simpleSelect('#select-isbn', 'Select a ISBN')
        this.selectISBN.disable()
    }
}
</script>