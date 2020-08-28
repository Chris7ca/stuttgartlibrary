<template>
    <div>
        <form ref="formWriter" @submit.prevent="handleSubmit">
            <div class="uk-grid uk-child-width-1-2 uk-margin" uk-grid>
                <div>
                    <div>
                        <label>Name</label>
                        <input type="text" class="uk-input" v-model="writer.name" required>
                    </div>
                </div>
                <div>
                    <div>
                        <label>Last Name</label>
                        <input type="text" class="uk-input" v-model="writer.lastname" required>
                    </div>
                </div>
            </div>
            <div class="uk-margin">
                <label class="uk-form-label">Origin Country</label>
                <input type="text" class="uk-input" v-model="writer.country" required>
            </div>
            <div class="uk-margin-medium">
                <button type="submit" class="uk-button uk-button-primary uk-box-shadow-hover-large uk-text-capitalize" :disabled="loader">
                    {{ mode }} writer <span v-if="loader" class="uk-margin-small-left" uk-spinner="ratio: 0.7"></span>
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
            writer: {
                name: '',
                lastname: '',
                country: ''
            }
        }
    },
    methods: {
        handleSubmit: function () {
            if ( this.mode == 'create' ) {
                this.storeWriter()
            } else {
                this.updateWriter()
            }
        },
        storeWriter: function () {
            this.loader = true
            axios.post(route('admin.writers.store'), this.writer)
            .then(response => {
                this.loader = false
                EventBus.$emit('writerCreated', response.data)
                UIkit.modal('#modal-writer').hide()
                UIkit.notification('Writer created', 'success')
            })
            .catch(error => {
                this.loader = false
                UIkit.notification(`Error store writer: ${error}`, 'danger')
            })
        },
        updateWriter: function () {
            this.loader = true
            axios.put(route('admin.writers.update', this.writer.id), this.writer)
            .then(response => {
                this.loader = false
                EventBus.$emit('writerUpdated', response.data)
                UIkit.modal('#modal-writer').hide()
                UIkit.notification('Writer updated', 'success')
            })
            .catch(error => {
                this.loader = false
                UIkit.notification(`Error update writer: ${error}`, 'danger')
            })
        },
        clearForm: function () {
            this.writer = {
                name: '',
                lastname: '',
                country: ''
            }
        }
    },
    created() {

        EventBus.$on('newWriter', () => {
            this.mode = 'create'
            this.clearForm()
        })

        EventBus.$on('editWriter', writer => {
            this.mode = 'update'
            this.clearForm()
            this.writer = Object.assign({}, writer)
        })
    }
}
</script>