<template>
    <div>
        <div class="uk-flex uk-flex-middle uk-flex-between">
            <div>
                <h6 v-if="paginatedWriters.total > 0" class="uk-margin-remove uk-text-muted">{{ paginatedWriters.total }} writers</h6>
            </div>
            <div class="uk-flex uk-flex-middle">
                <div class="uk-margin-small-right">
                    <a role="button" uk-toggle="#modal-writer" uk-icon="plus-circle" uk-tooltip="Create a new writer" @click="newWriter"></a>
                </div>
                <div class="uk-inline uk-align-right uk-margin-remove-vertical uk-margin-remove-left">
                    <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: search"></span>
                    <input class="uk-input" type="text" v-model="querySearch" placeholder="Search writer...">  
                </div>
            </div>
        </div>
        <div class="uk-margin-medium">
            <table class="uk-table uk-table-divider uk-table-responsive">
                <thead>
                    <tr>
                        <th>
                            <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="sortBy('fullname')">
                                <span v-if="options.sortBy == 'fullname'" :uk-icon="options.sortType == 'asc' ? 'arrow-down' : 'arrow-up'"></span> Full Name
                            </a>
                        </th>
                        <th>
                            <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="sortBy('country')">
                                <span v-if="options.sortBy == 'country'" :uk-icon="options.sortType == 'asc' ? 'arrow-down' : 'arrow-up'"></span> Origin Country
                            </a>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <table-body-placeholder :rows="10" :columns="3" v-if="loader"></table-body-placeholder>
                <tbody v-else>
                    <tr v-for="(writer, wI) in paginatedWriters.data" :key="wI">
                        <td v-html="querySearchInAtt(writer.fullname)"></td>
                        <td v-html="querySearchInAtt(writer.country)"></td>
                        <td>
                            <span v-if="busyWriters.indexOf(writer.id) >= 0" uk-spinner></span>
                            <ul v-else class="uk-iconnav">
                                <li uk-tooltip="Edit writer">
                                    <a role="button" class="uk-preserve-width" uk-icon="pencil" uk-toggle="#modal-writer" @click="editWriter(wI)"></a>
                                </li>
                                <li uk-tooltip="Delete writer">
                                    <a role="button" class="uk-preserve-width" uk-icon="trash" @click="deleteWriter(wI, writer.id)"></a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!loader && paginatedWriters.total == 0">
                    <tr>
                        <td colspan="3"><h5>No writers found</h5></td>
                    </tr>
                </tfoot>
            </table>
            <pagination :sizeData="paginatedWriters.total" :per-page="10" @updatePage="updatePage"></pagination>
        </div>
    </div>
</template>

<script>

import { EventBus } from './../../bus'

export default {
    data() {
        return {
            loader: true,
            paginatedWriters: {},
            busyWriters: [],
            querySearch: '',
            promisesTyping: [],
            options: {
                page: 1,
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
                    this.loadWriters()
                })
            }
        }
    },
    methods: {
        loadWriters: function () {
            this.loader = true
            axios.get(route('admin.writers', this.options))
            .then(response => {
                this.loader = false
                this.paginatedWriters = response.data
            })
            .catch(error => {
                this.loader = false
                UIkit.notification(`Error loading writers: ${error}`, 'danger')
            })
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
            this.loadWriters()
        },
        newWriter: function () {
            EventBus.$emit('newWriter')
        },
        editWriter: function (index) {
            EventBus.$emit('editWriter', this.paginatedWriters.data[index])
        },
        deleteWriter: function(index, id) {
            const self = this

            UIkit.modal.confirm(`Are you sure want delete this writer? 
            This writer will be removed but references to it will remain`)
            .then(function () {

                self.busyWriters.push(id)
                
                axios.delete(route('admin.writers.delete', id))
                .then(response => {
                    self.paginatedWriters.data.splice(index, 1)
                    self.paginatedWriters.total--
                    UIkit.notification('Writer deleted', 'success')
                })
                .catch(error => {
                    self.busyWriters = self.busyWriters.filter(bc => bc != id)
                    UIkit.notification('Writer deleted', 'danger')
                })
            }, () => {})
        },
        updatePage: function (page) {
            this.options.page = page
            this.loadWriters()
        }
    },
    created() {
        
        this.loadWriters()

        EventBus.$on('writerCreated', writer => {
            this.paginatedWriters.total++
            this.paginatedWriters.data.push(writer)
        })

        EventBus.$on('writerUpdated', writer => {
            const index = this.paginatedWriters.data.findIndex(w => w.id == writer.id)
            if ( index >= 0 ) {
                this.paginatedWriters.data[index] = writer
                this.$forceUpdate()
            }
        })
    }
}
</script>