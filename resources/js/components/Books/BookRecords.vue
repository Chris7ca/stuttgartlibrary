<template>
    <div>
        <ul uk-tab>
            <li><a role="button">Records</a></li>
            <li><a role="button">Add Records</a></li>
        </ul>
        <ul class="uk-switcher uk-margin">
            <li>
                <table class="uk-table uk-table-divider uk-table-responsive uk-margin">
                    <thead>
                        <tr>
                            <th>ISBN</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <table-body-placeholder :rows="10" :columns="3" v-if="loader"></table-body-placeholder>
                    <tbody v-else>
                        <tr v-for="(record, rI) in records" :key="rI">
                            <td>
                                <input type="text" class="uk-input uk-form-blank" :ref="`input-isbn-${rI}`" @keydown="validateISBN($event, rI)" v-model="record.isbn" :readonly="editingRecords.indexOf(record.id) < 0">
                            </td>
                            <td>
                                <input type="number" class="uk-input uk-form-blank" v-model="record.price" min="0" :readonly="editingRecords.indexOf(record.id) < 0">
                            </td>
                            <td>
                                <span v-if="busyRecords.indexOf(record.id) >= 0" uk-spinner></span>
                                <ul v-else class="uk-iconnav">
                                    <li v-if="editingRecords.indexOf(record.id) < 0" uk-tooltip="Edit information">
                                        <a role="button" class="uk-preserve-width" uk-icon="pencil" @click="editRecord(rI, record.id)"></a>
                                    </li>
                                    <li v-else uk-tooltip="Update information">
                                        <a role="button" class="uk-preserve-width" uk-icon="check" @click="updateRecord(rI, record.id)"></a>
                                    </li>
                                    <li uk-tooltip="Delete record">
                                        <a role="button" class="uk-preserve-width" uk-icon="trash" @click="deleteRecord(rI, record.id)"></a>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot v-if="!loader && records.length == 0">
                        <tr>
                            <td colspan="3">
                                <h4>Not records found</h4>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </li>
            <li>
                <form ref="formRecord" @submit.prevent="storeRecord">
                    <div class="uk-margin">
                        <label>ISBN</label>
                        <input class="uk-input" type="text" @keydown="validateISBN($event, undefined)" v-model="record.isbn" placeholder="1234567890123" required>
                    </div>
                    <div class="uk-margin">
                        <label>Price</label>
                        <input class="uk-input" type="text" v-model="record.price" min="0" placeholder="$ 0.0" required>
                    </div>
                    <div class="uk-margin-medium">
                        <button class="uk-button uk-button-primary uk-box-shadow-hover-large" :disabled="savingRecord">
                            Save record <span v-if="savingRecord" class="uk-margin-small-left" uk-spinner="ratio: 0.7"></span>
                        </button>
                    </div>
                </form>
            </li>
        </ul>
    </div>
</template>

<script>

import { EventBus } from './../../bus'

export default {
    data() {
        return {
            loader: true,
            savingRecord: false,
            book: {},
            record: {
                isbn: '', 
                price: ''
            },
            records: [],
            busyRecords: [],
            editingRecords: []
        }
    },
    methods: {
        loadRecords: function () {
            this.loader = true
            axios.get(route('admin.books.records', { id: this.book.id }))
            .then(records => {
                this.loader = false
                this.records = records.data
            })
            .catch(error => {
                this.loader = false
                UIkit.notification(`Error loading records: ${error}`, 'danger')
            })
        },
        storeRecord: function () {
            if ( this.validateRecordBeforeSave(this.record) ) {
                this.savingRecord = true
                axios.post(route('admin.books.records.store', { id: this.book.id }), this.record)
                .then(record => {
                    this.savingRecord = false
                    this.record.isbn = ''
                    this.record.price = ''
                    this.records.push(record.data)
                    EventBus.$emit('recordCreated', this.book.id)
                    UIkit.notification('Record created', 'success')
                })
                .catch(error => {
                    this.savingRecord = false
                    UIkit.notification(`Error created record: ${error}`, 'danger')
                })
            }
            else {
                UIkit.notification('The ISBN or price are incorrect', 'warning')
            }
        },
        editRecord: function (index, id) {
            this.editingRecords.push(id)
            this.$refs[`input-isbn-${index}`][0].focus()
        },
        updateRecord: function (index, id) {
            const record = this.records[index]
            if ( this.validateRecordBeforeSave(record) ) {
                this.busyRecords.push(id)
                axios.put(route('admin.books.records.update', { book: this.book.id, id }), record)
                .then(response => {
                    this.busyRecords = this.busyRecords.filter(iR => iR != id)
                    this.editingRecords = this.editingRecords.filter(iR => iR != id)
                    UIkit.notification('Record updated', 'success')
                })
                .catch(error => {
                    this.busyRecords = this.busyRecords.filter(iR => iR != id)
                    UIkit.notification(`Error: ${error}`, 'danger')
                })
            } 
            else {
                UIkit.notification('The ISBN or price are incorrect', 'warning')
            }
        },
        deleteRecord: function (index, id) {
            const self = this
            UIkit.modal.confirm(`Are you sure want delete this record?`)
            .then(function () {

                UIkit.modal('#modal-book-records').show()
                self.busyRecords.push(id)
                axios.delete(route('admin.books.records.delete', { book: self.book.id, id }))
                .then(response => {
                    self.busyRecords = self.busyRecords.filter(iR => iR != id)
                    self.records.splice(index, 1)
                    EventBus.$emit('recordDeleted', self.book.id)
                    UIkit.notification('Record deleted', 'success')
                })
                .catch(error => {
                    self.busyRecords = self.busyRecords.filter(iR => iR != id)
                    UIkit.notification(`Error: ${error}`, 'danger')
                })
            }, () => {})
        },
        validateISBN: function(event, index) {
            const key = event.keyCode
            const lengthISBN = index >= 0 ? this.records[index].isbn.length : this.record.isbn.length
            const numberKeyCodes = [...Array(10).keys()].map(i => i+48)
            const numpadKeyCodes = [...Array(10).keys()].map(i => i+96)
            const actionsKeyCodes = [8,9,37,39,46,116,123]
            const validKeyCode = numberKeyCodes.includes(key) 
                || numpadKeyCodes.includes(key) 
                || actionsKeyCodes.includes(key)

            if ( !validKeyCode ) {
                event.preventDefault()
            } 
            else if ( lengthISBN == 13 && (numpadKeyCodes.includes(key) || numberKeyCodes.includes(key)) ) {
                event.preventDefault()
            }
        },
        validateRecordBeforeSave: function (record) {
            const validISBN = /\d{13}$/g.test(record.isbn)
            const validPrice = !isNaN(record.price) && record.price >= 0

            return validISBN && validPrice
        }
    },
    created() {
        EventBus.$on('viewRecords', book => {
            this.book = book
            this.busyRecords = []
            this.editingRecords = []
            this.loadRecords()
            UIkit.modal('#modal-book-records').show()
        })
    }
}
</script>