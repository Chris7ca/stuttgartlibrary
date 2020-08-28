<template>
    <div>
        <div class="uk-flex uk-flex-middle uk-flex-between">
            <div>
                <h6 v-if="paginatedRecords.total > 0" class="uk-margin-remove uk-text-muted">{{ paginatedRecords.total }} borrows</h6>
            </div>
            <div class="uk-flex uk-flex-middle">
                <div class="uk-margin-small-right">
                    <a role="button" uk-toggle="#modal-borrow" uk-icon="plus-circle" uk-tooltip="Create a new borrow" @click="newBorrow"></a>
                </div>
                <div class="uk-inline uk-align-right uk-margin-remove-vertical uk-margin-remove-left">
                    <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: search"></span>
                    <input class="uk-input" type="text" v-model="querySearch" placeholder="Search record...">  
                </div>
            </div>
        </div>
        <div class="uk-flex uk-flex-right uk-margin">
            <div>
                <label class="uk-margin-small-right">
                    <input type="radio" class="uk-radio" v-model="options.exclude" :value="null"> Show all
                </label>
                <label class="uk-margin-small-right">
                    <input type="radio" class="uk-radio" v-model="options.exclude" value="return_date"> Close to expire
                </label>
                <label>
                    <input type="radio" class="uk-radio" v-model="options.exclude" value="-return_date"> Expired
                </label>
            </div>
        </div>
        <div class="uk-margin">
            <table class="uk-table uk-table-divider uk-table-responsive">
                <thead>
                    <tr>
                        <th>
                            <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="sortBy('user')">
                                <span v-if="options.sortBy == 'user'" :uk-icon="options.sortType == 'asc' ? 'arrow-down' : 'arrow-up'"></span> User
                            </a>
                        </th>
                        <th>
                            <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="sortBy('book')">
                                <span v-if="options.sortBy == 'book'" :uk-icon="options.sortType == 'asc' ? 'arrow-down' : 'arrow-up'"></span> Book
                            </a>
                        </th>
                        <th>
                            <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="sortBy('isbn')">
                                <span v-if="options.sortBy == 'isbn'" :uk-icon="options.sortType == 'asc' ? 'arrow-down' : 'arrow-up'"></span> ISBN
                            </a>
                        </th>
                        <th>
                            <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="sortBy('delivery_date')">
                                <span v-if="options.sortBy == 'delivery_date'" :uk-icon="options.sortType == 'asc' ? 'arrow-down' : 'arrow-up'"></span> Delibery <br>Date
                            </a>
                        </th>
                        <th>
                            <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="sortBy('deadline')">
                                <span v-if="options.sortBy == 'deadline'" :uk-icon="options.sortType == 'asc' ? 'arrow-down' : 'arrow-up'"></span> Deadline
                            </a>
                        </th>
                        <th>
                            <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="sortBy('return_date')">
                                <span v-if="options.sortBy == 'return_date'" :uk-icon="options.sortType == 'asc' ? 'arrow-down' : 'arrow-up'"></span> Return <br>Date
                            </a>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <table-body-placeholder :rows="10" :columns="7" v-if="loader"></table-body-placeholder>
                <tbody v-else>
                    <tr :class="{'uk-background-warning': closeToExpire(record.days_to_expire), 'uk-background-danger': expired(record.days_to_expire)}" v-for="(record, rI) in paginatedRecords.data" :key="rI">
                        <td v-html="querySearchInAtt(record.user)"></td>
                        <td v-html="querySearchInAtt(record.book)"></td>
                        <td v-html="querySearchInAtt(record.isbn)"></td>
                        <td v-html="querySearchInAtt(record.delivery_date)"></td>
                        <td v-html="querySearchInAtt(record.deadline)"></td>
                        <td v-html="querySearchInAtt(record.return_date || 'Book not returned')"></td>
                        <td>
                            <span v-if="busyRecords.indexOf(record.id) >= 0"></span>
                            <a v-else role="button" class="uk-preserve-width" uk-icon="reply" uk-tooltip="Register return" @click="giveBackBook(rI, record)"></a>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!loader && paginatedRecords.total == 0">
                    <tr>
                        <td colspan="7"><h5>No records found</h5></td>
                    </tr>
                </tfoot>
            </table>
            <pagination :sizeData="paginatedRecords.total" :per-page="10" @updatePage="updatePage"></pagination>
        </div>
    </div>
</template>

<script>

import { EventBus } from './../../bus'

export default {
    data() {
        return {
            loader: true,
            busyRecords: [],
            paginatedRecords: [],
            promisesTyping: [],
            querySearch: '',
            options: {
                page: 1,
                exclude: null,
                search: null,
                sortType: null,
                sortBy: null,
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
                    this.options.search = val
                    this.loadRecords()
                })
            }
        },
        'options.exclude': function (val) {
            this.loadRecords()
        }
    },
    methods: {
        loadRecords: function () {
            this.loader = true
            axios.get(route('admin.books.borrowed', this.options))
            .then(response => {
                this.loader = false
                this.paginatedRecords = response.data
            })
            .catch(error => {
                this.loader = false
                UIkit.notification(`Error loading borrowed books: ${error}`, 'danger')
            })
        },
        closeToExpire: function (days) {
            return days <= 7 && days > 0
        },
        expired: function (days) {
            return days != null && days <= 0
        },
        querySearchInAtt: function (attribute) {
            if ( this.querySearch && this.querySearch.length > 0 ) {
                const regExp = new RegExp(this.querySearch, "gi")
                return attribute.replace(regExp, "<b class='uk-text-primary'>$&</b>")
            }
            return attribute
        },
        sortBy: function (sortBy) {
            this.options.sortBy = sortBy
            this.options.sortType = this.options.sortType == 'asc' ? 'desc' : 'asc'
            this.loadRecords()
        },
        giveBackBook: function (index, record) {
            const self = this

            UIkit.modal.confirm(`Confirm action`)
            .then(function () {
                
                self.busyRecords.push(record.id)

                axios.put(route('admin.books.borrows.back', record.id))
                .then(response => {
                    self.paginatedRecords.data.splice(index, 1)
                    self.paginatedRecords.total--
                    UIkit.notification('Information saved', 'success')
                })
                .catch(error => {
                    self.busyRecords = self.busyRecords.filter(bc => bc != record.id)
                    UIkit.notification(`Error: ${error}`, 'danger')
                })
            }, () => {})
        },
        newBorrow: function (record) {
            EventBus.$emit('newBorrow', record)
        },
        updatePage: function(page) {
            this.options.page = page
            this.loadRecords()
        }
    },
    created() {
        
        this.loadRecords()

        EventBus.$on('borrowBookCreated', () => {
            this.paginatedRecords.total++
        })

        EventBus.$on('bookWasReturned', id => {
            const index = this.paginatedRecords.data.findIndex(r => r.id == id)
            if (index >= 0) {
                this.paginatedRecords.total--
                this.paginatedRecords.data[index].splice(index, 1)
            }
        })
    }
}
</script>