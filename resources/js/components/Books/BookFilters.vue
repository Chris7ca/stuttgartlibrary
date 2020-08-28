<template>
    <div>
        <p class="uk-text-bold">
            <span class="uk-margin-small-right" uk-icon="settings"></span> Filters
        </p>
        <div class="uk-margin-bottom">
            <p class="uk-margin-small" v-if="hasCategoryFilter">
                <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="resetFilter('category')">
                    <span class="uk-margin-small-right" uk-icon="close"></span> {{ filters.category }}
                </a>
            </p>
            <p class="uk-margin-small" v-if="hasWriterFilter">
                <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="resetFilter('writer')">
                    <span class="uk-margin-small-right" uk-icon="close"></span> {{ filters.writer.split('-').join(' ') }}
                </a>
            </p>
            <p class="uk-margin-small" v-if="hasYearFilter">
                <a role="button" class="uk-button uk-padding-remove uk-text-uppercase" @click="resetFilter('year')">
                    <span class="uk-margin-small-right" uk-icon="close"></span> Published in {{ filters.year }}
                </a>
            </p>
        </div>
        <div class="uk-margin-medium">
            <ul uk-accordion>
                <li>
                    <a class="uk-accordion-title" role="button">Categories</a>
                    <div class="uk-accordion-content">
                        <select id="select-category" v-model="filters.category" @change="filtersUpdated">
                            <option v-for="(category, cI) in categories" :key="cI" :value="category.slug">{{ category.title }}</option>
                        </select>
                    </div>
                </li>
                <li>
                    <a class="uk-accordion-title" role="button">Published year</a>
                    <div class="uk-accordion-content">
                        <div class="uk-inline">
                            <a class="uk-form-icon uk-form-icon-flip" role="button" uk-icon="icon: search" @click="setYearFilter"></a>
                            <input :class="{'uk-input': true, 'uk-form-danger': !validateYear}" type="text" placeholder="1990" v-model="temporalYearFilter">
                        </div>
                    </div>
                </li>
                    <li>
                    <a class="uk-accordion-title" role="button">Author</a>
                    <div class="uk-accordion-content">
                        <select id="select-writer" v-model="filters.writer" @change="filtersUpdated"></select>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    props: ['category','author'],
    data() {
        return {
            categories: [],
            temporalYearFilter: '',
            filters: {
                category: this.category || null,
                writer: this.author || null,
                year: null
            }
        }
    },
    watch: {
        temporalYearFilter: function(val, oldVal) {
            if ( val.length > 4 || isNaN(val) ) {
                this.temporalYearFilter = oldVal
            }
        },
        category: function (category) {
            this.filters.category = category
            this.$emit('filtersUpdated', this.filters)
        },
        author: function (writer) {
            this.filters.writer = writer
            this.$emit('filtersUpdated', this.filters)
        }
    },
    computed: {
        validateYear: function () {
            return (this.temporalYearFilter >= 1940
                && this.temporalYearFilter <= new Date().getFullYear())
                || this.temporalYearFilter == ''
        },
        hasCategoryFilter: function () {
            return this.filters.category
        },
        hasWriterFilter: function () {
            return this.filters.writer
        },
        hasYearFilter: function () {
            return this.filters.year
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
        filtersUpdated: function () {
            this.$emit('filtersUpdated', this.filters)
        },
        setYearFilter: function () {
            if ( this.temporalYearFilter != '' && this.validateYear ) {
                this.filters.year = this.temporalYearFilter
                this.$emit('filtersUpdated', this.filters)
            }
        },
        resetFilter: function (filter) {
            if ( this.filters.hasOwnProperty(filter) ) {
                this.filters[filter] = null
                this.$emit('filtersUpdated', this.filters)
            }
        }
    },
    created() {
        this.loadCategories()
    },
    mounted() {

        new SlimSelect({
            select: '#select-category',
            placeholder: 'Select a category'
        })

        new SlimSelect({
            select: '#select-writer',
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
                            value: writer.slug
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