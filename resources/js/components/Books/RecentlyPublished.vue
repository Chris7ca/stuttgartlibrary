<template>
    <div>
        <div v-if="loader">
            <p>Loading books <span class="uk-margin-small-left" uk-spinner="ratio: 0.7"></span></p>
        </div>

        <div v-else class="uk-position-relative uk-visible-toggle" tabindex="-1" uk-slideshow="animation: fade; autoplay: true">
            <div v-if="books.length > 1" class="uk-position-top-left uk-position-z-index">
                <div class="uk-flex uk-flex-middle">
                    <a class="uk-position-small uk-control-slide" role="button" uk-icon="arrow-left" @click="activeControlSlider" uk-slideshow-item="previous"></a>
                    <a class="uk-position-small uk-control-slide active" role="button" uk-icon="arrow-right" @click="activeControlSlider" uk-slideshow-item="next"></a>
                </div>
            </div>
            <ul class="uk-slideshow-items uk-height-large">
                <li v-for="(book, bI) in books" :key="bI">
                    <div class="uk-position-center uk-text-center">
                        <img class="thumb-book-slider uk-box-shadow-large" :data-src="book.image" uk-img :uk-slideshow-parallax="bI % 2 === 0 ? parallaxVer : parallaxHorz">
                        <h2 :uk-slideshow-parallax="bI % 2 === 0 ? parallaxTextVertD : parallaxTextHorzD">
                            {{ book.title }} - by <a :href="route('library.view', { author: book.author_slug })" class="uk-text-primary">{{ book.author }}</a>
                        </h2>
                        <p :uk-slideshow-parallax="bI % 2 === 0 ? parallaxTextVertD : parallaxTextHorzD">Published: {{ book.published_date }}</p>
                        <a :uk-slideshow-parallax="bI % 2 === 0 ? parallaxTextVertD : parallaxTextHorzD" :href="route('books.show', book.slug)">Show book</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            loader: true,
            books: [],
            parallaxHorz: 'x: -100,100',
            parallaxTextHorzD: 'x: -200,200',
            parallaxVer: 'y: 50,0,0; opacity: 1,1,0',
            parallaxTextVertD: 'y: -50,0,0; opacity: 1,1,0'
        }
    },
    methods: {
        getBooks: function () {
            axios.get(route('books.recently'))
            .then(books => {
                this.loader = false
                this.books = books.data
            })
            .catch(error => {
                this.loader = false
                UIkit.notification(`Error loading books: ${error}`, 'danger')
            })
        },
        activeControlSlider: function (event) {
            const element = event.currentTarget
            document.querySelectorAll(".uk-control-slide.active").forEach(e => element != e ? e.classList.remove('active') : null)
            setTimeout(function () {
                element.classList.add('active')
            }, 100)
        }
    },
    created() {
        this.getBooks();
    }
}
</script>