<template>
    <div>
        <form ref="formCategory" @submit.prevent="handleSubmit">
            <div class="uk-margin">
                <label class="uk-form-label">Title</label>
                <input type="text" class="uk-form-controls uk-input" v-model="category.title" placeholder="Title of category" required>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label">Description</label>
                <textarea class="uk-form-controls uk-textarea" rows="5" v-model="category.description" required></textarea>
            </div>
            <div class="uk-margin-medium">
                <button type="submit" class="uk-button uk-button-primary uk-box-shadow-hover-large uk-text-capitalize" :disabled="loader">
                    {{ mode }} category <span v-if="loader" class="uk-margin-small-left" uk-spinner="ratio: 0.7"></span>
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
            loader: false,
            mode: 'create',
            category: {
                title: '',
                description: ''
            }
        }
    },
    methods: {
        handleSubmit: function () {
            if ( this.mode == 'create' ) {
                this.storeCategory()
            } else {
                this.updateCategory()
            }
        },
        storeCategory: function () {
            this.loader = true
            axios.post(route('admin.categories.store'), this.category)
            .then(response => {
                this.loader = false
                this.clearForm()
                EventBus.$emit('categoryCreated', response.data)
                UIkit.modal('#modal-category').hide()
                UIkit.notification('Category created', 'success')
            })
            .catch(error => {
                this.loader = false
                UIkit.notification(`Error: ${error}`, 'danger')
            })
        },
        updateCategory: function () {
            this.loader = true
            axios.put(route('admin.categories.update', this.category.id), this.category)
            .then(response => {
                this.loader = false
                this.clearForm()
                EventBus.$emit('categoryUpdated', response.data)
                UIkit.modal('#modal-category').hide()
                UIkit.notification('Category updated', 'success')
            })
            .catch(error => {
                this.loader = false
                UIkit.notification(`Error: ${error}`, 'danger')
            })
        },
        clearForm: function () {
            this.category = {
                title: '',
                description: ''
            }
        }
    },
    created() {
        
        EventBus.$on('newCategory', () => {
            this.mode = 'create'
            this.clearForm()
        })
        
        EventBus.$on('editCategory', (category) => {
            this.mode = 'update'
            this.clearForm()
            this.category = Object.assign({}, category)
        })
    }
}
</script>