<template>
    <div>
        <ul v-if="!loader" class="uk-list uk-list-disc uk-list-primary uk-margin-medium">
            <li v-if="categories.length == 0">
                No categories found
            </li>
            <li v-else v-for="(category, cI) in categories" :key="cI">
                <a :href="route('library.view', { category: category.slug })">{{ category.title }} ({{ category.books_count }})</a>
            </li>
        </ul>
        
        <div v-else>
            <p>Loading categories <span class="uk-margin-small-left" uk-spinner="ratio: 0.7"></span></p>
        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            loader: true,
            categories: []
        }
    },
    methods: {
        loadCategories: function () {
            axios.get(route('books.categories', { countBooks: true }))
            .then(categories => {
                this.loader = false
                this.categories = categories.data
            })
            .catch(error => {
                this.loader = false
                UIkit.notification(`Error loading categories: ${error}`, 'danger')
            })
        }
    },
    created() {
        this.loadCategories()
    }
}
</script>