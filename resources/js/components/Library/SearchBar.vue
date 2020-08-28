<template>
    <div>
        <div class="uk-navbar-item uk-width-expand uk-search-bar">
            <div class="uk-search uk-search-navbar uk-inline uk-width-1-1">
                <span v-if="loader" class="uk-form-icon uk-form-icon-flip" uk-spinner="ratio: 0.8"></span>
                <a v-else class="uk-form-icon uk-form-icon-flip" uk-close uk-toggle="target: .nav-overlay; animation: uk-animation-fade" role="button"></a>
                <input class="uk-search-input" type="search" placeholder="Search..." autofocus v-model="queryString" @click="preventClick">
            </div>
        </div>
        <div :id="id" uk-dropdown="pos: bottom-justify; boundary: .uk-search-bar; boundary-align: true; mode:click">
            <div>
                <h4 class="uk-text-bold">Books</h4>
                <p v-if="books.length == 0" class="uk-text-meta uk-margin-small-left">Books not found</p>
                <ul v-else class="uk-list uk-list-striped">
                    <li v-for="(book, bI) in books" :key="bI">
                        <a :href="route('books.show', book.slug)">{{ book.title }}</a>
                    </li>
                </ul>
            </div>
            <div>
                <h4 class="uk-text-bold">Writers</h4>
                <p v-if="writers.length == 0" class="uk-text-meta uk-margin-small-left">Writers not found</p>
                <ul v-else class="uk-list uk-list-striped">
                    <li v-for="(writer, wI) in writers" :key="wI">
                        <a :href="route('library.view', { author: writer.slug })">{{ writer.name }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['id'],
    data() {
        return {
            loader: false,
            books: [],
            writers: [],
            queryString: '',
            promisesTyping: []
        }
    },
    watch: {
        queryString: function (val) {
            if ( val ) {
                this.promisesTyping.push(new Promise((resolve, reject) => {
                    setTimeout(() => {
                        resolve()
                    }, 1000)
                }))

                if (this.promisesTyping.length == 1) {
                    Promise.all(this.promisesTyping).then(() => {
                        this.promisesTyping = []
                        this.searchInLibrary()
                    })
                }
            }
        }
    },
    methods: {
        preventClick: function(event) {
            event.preventDefault();
            event.stopPropagation();
        },
        showDropdown: function () {
        },
        searchInLibrary: function () {
            this.loader = true
            UIkit.dropdown(`#${this.id}`).show()
            axios.get(route('library.search', { queryString: this.queryString }))
            .then(response => {
                this.loader = false
                this.books = response.data.books
                this.writers = response.data.writers
            })
            .catch(error => {
                this.loader = false
                showErrorMessage('Error searching', error)
            })
        }
    }
}
</script>