<template>
    <div>

        <div v-if="loader">
            <p>Loading books <span class="uk-margin-small-left" uk-spinner="ratio: 0.7"></span></p>
        </div>
        
        <div v-else class="uk-margin-large" uk-slider>
            <div v-if="books.length > 1">
                <a class="uk-padding-small" role="button" uk-icon="icon: arrow-left; ratio: 1.3" uk-slider-item="previous"></a>
                <a class="uk-padding-small" role="button" uk-icon="icon: arrow-right; ratio: 1.3" uk-slider-item="next"></a>
            </div>
            <div class="uk-position-relative uk-visible-toggle" tabindex="-1">
        
                <ul class="uk-slider-items uk-child-width-1-5@xl uk-child-width-1-4@l uk-child-width-1-3@m uk-child-width-1-2@s uk-grid">
                    <li v-for="(book, bI) in books" :key="bI">
                        <div class="uk-card uk-card-body uk-card-bordered uk-box-shadow-medium uk-background-cover uk-height-large uk-position-relative" :data-src="book.image" uk-img>
                            <div v-if="book.existences == book.borrowed_books" class="uk-position-top-right uk-padding-small">
                                <span class="uk-label uk-label-danger uk-box-shadow-medium">Not available</span>
                            </div>
                        </div>
                        <h3 class="uk-margin-small-bottom uk-margin-small-top uk-text-bold">
                            <a class="uk-link-reset" :href="route('books.show', book.slug)">{{ book.title }}</a>
                        </h3>
                        <a :href="route('library.view', { author: book.author_slug })" class="uk-margin-remove">{{ book.author }}</a>
                    </li>
                </ul>
        
            </div>        
        </div>

    </div>
</template>

<script>
export default {
    props: ['resourceRoute'],
    data() {
        return {
            loader: true,
            books: []
        }
    },
    methods: {
        getBooks: function () {
            axios.get(this.resourceRoute)
            .then(books => {
                this.loader = false
                this.books = books.data
            })
            .catch(error => {
                this.loader = false
                UIkit.notification(`Error loading books ${error}`, 'danger')
            })
        }
    },
    created() {
        if ( this.resourceRoute ) {
            this.getBooks()
        }
    }
}
</script>