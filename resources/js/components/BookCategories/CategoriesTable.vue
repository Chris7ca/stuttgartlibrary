<template>
    <div>
        <div class="uk-flex uk-flex-middle uk-flex-between">
            <div>
                <h6 v-if="paginatedCategories.total > 0" class="uk-margin-remove uk-text-muted">{{ paginatedCategories.total }} categories</h6>
            </div>
            <div class="uk-flex uk-flex-middle">
                <div class="uk-margin-small-right">
                    <a role="button" uk-toggle="#modal-category" uk-icon="plus-circle" uk-tooltip="Create a new category" @click="newCategory"></a>
                </div>
                <div class="uk-inline uk-align-right uk-margin-remove-vertical uk-margin-remove-left">
                    <span class="uk-form-icon uk-form-icon-flip" uk-icon="icon: search"></span>
                    <input class="uk-input" type="text" v-model="querySearch" placeholder="Search category...">  
                </div>
            </div>
        </div>
        <div class="uk-margin-medium">
            <table class="uk-table uk-table-divider uk-table-responsive">
                <thead>
                    <tr>
                        <th>
                            <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="sortBy('title')">
                                <span v-if="options.sortBy == 'title'" :uk-icon="options.sortType == 'asc' ? 'arrow-down' : 'arrow-up'"></span> Title
                            </a>
                        </th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <table-body-placeholder :rows="10" :columns="3" v-if="loader"></table-body-placeholder>
                <tbody v-else>
                    <tr v-for="(category, cI) in paginatedCategories.data" :key="cI">
                        <td v-html="querySearchInAtt(category.title)"></td>
                        <td v-html="querySearchInAtt(category.description)"></td>
                        <td>
                            <span v-if="busyCategories.indexOf(category.id) >= 0" uk-spinner></span>
                            <ul v-else class="uk-iconnav">
                                <li uk-tooltip="Edit category">
                                    <a role="button" class="uk-preserve-width" uk-icon="pencil" uk-toggle="#modal-category" @click="editCategory(cI)"></a>
                                </li>
                                <li uk-tooltip="Delete category">
                                    <a role="button" class="uk-preserve-width" uk-icon="trash" @click="deleteCategory(cI, category.id)"></a>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
                <tfoot v-if="!loader && paginatedCategories.total == 0">
                    <tr>
                        <td colspan="3"><h5>No caregories found</h5></td>
                    </tr>
                </tfoot>
            </table>
            <pagination :sizeData="paginatedCategories.total" :per-page="10" @updatePage="updatePage"></pagination>
        </div>
    </div>
</template>

<script>

import { EventBus } from './../../bus'

export default {
    data() {
        return {
            loader: true,
            paginatedCategories: [],
            busyCategories: [],
            promisesTyping: [],
            querySearch: '',
            options: {
                page: 1,
                sortType: null,
                sortBy: null,
                search: null
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
                    this.loadCategories()
                })
            }
        }
    },
    methods: {
        loadCategories: function () {
            this.loader = true,
            axios.get(route('admin.categories', this.options))
            .then(response => {
                this.loader = false
                this.paginatedCategories = response.data
            })
            .catch(error => {
                this.loader = false
                UIkit.notification(`Error loading categories: ${error}`, 'danger')
            })
        },
        sortBy: function (sortBy) {
            this.options.sortBy = sortBy
            this.options.sortType = this.options.sortType == 'asc' ? 'desc' : 'asc'
            this.loadCategories()
        },
        updatePage: function (page) {
            this.options.page = page
            this.loadCategories()
        },
        querySearchInAtt: function (attribute) {
            if ( this.querySearch && this.querySearch.length > 0 ) {
                const regExp = new RegExp(this.querySearch, "gi")
                return attribute.replace(regExp, "<b class='uk-text-primary'>$&</b>")
            }

            return attribute
        },
        newCategory: function () {
            EventBus.$emit('newCategory')
        },
        editCategory: function (index) {
            EventBus.$emit('editCategory', this.paginatedCategories.data[index])
        },
        deleteCategory: function(index, id) {
            const self = this

            UIkit.modal.confirm(`Are you sure want delete this category? 
            This category will be removed but references to it will remain`)
            .then(function () {

                self.busyCategories.push(id)
                
                axios.delete(route('admin.categories.delete', id))
                .then(response => {
                    self.paginatedCategories.data.splice(index, 1)
                    self.paginatedCategories.total--
                    UIkit.notification('Category deleted', 'success')
                })
                .catch(error => {
                    self.busyCategories = self.busyCategories.filter(bc => bc != id)
                    UIkit.notification('Category deleted', 'danger')
                })
            }, () => {})
        }
    },
    created() {
        
        this.loadCategories()

        EventBus.$on('categoryCreated', category => {
            this.paginatedCategories.data.push(category)
            this.paginatedCategories.total++
        })
        
        EventBus.$on('categoryUpdated', category => {
            const index = this.paginatedCategories.data.findIndex(c => c.id == category.id)
            if ( index >= 0 ) {
                this.paginatedCategories.data[index] = category
                this.$forceUpdate()
            }
        })
    }
}
</script>