require('./bootstrap')

window.Vue = require('vue')

Vue.mixin({
    methods: {
        route: route // for Ziggy package
    },
})

Vue.component('bookcategories-list',        require('./components/BookCategories/BookCategoryList.vue').default)
Vue.component('recently-published-books',   require('./components/Books/RecentlyPublished.vue').default)
Vue.component('slider-books',               require('./components/Books/SliderBooks.vue').default)
Vue.component('search-bar',                 require('./components/Library/SearchBar.vue').default)

const app = new Vue({
    el: '#app',
})