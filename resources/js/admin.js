require('./bootstrap')

import VueContentPlaceholders from 'vue-content-placeholders'

window.SlimSelect = require('slim-select')

window.Vue = require('vue')

Vue.mixin({
    methods: {
        route: route // for Ziggy package
    },
})

Vue.use(VueContentPlaceholders)

Vue.component('table-body-placeholder',     require('./components/TableBodyPlaceholder.vue').default)
Vue.component('loader-overlay',             require('./components/LoaderOverlay.vue').default)
Vue.component('pagination',                 require('./components/Pagination.vue').default)

Vue.component('book-records',               require('./components/Books/BookRecords.vue').default)
Vue.component('books-table',                require('./components/Books/BooksTable.vue').default)
Vue.component('form-book',                  require('./components/Books/FormBook.vue').default)

Vue.component('categories-table',           require('./components/BookCategories/CategoriesTable.vue').default)
Vue.component('form-category',              require('./components/BookCategories/FormCategory.vue').default)

Vue.component('writers-table',              require('./components/Writers/WritersTable.vue').default)
Vue.component('form-writer',                require('./components/Writers/FormWriter.vue').default)

Vue.component('borrowed-books-table',       require('./components/Books/BorrowedBooksTable.vue').default)
Vue.component('borrow-book',                require('./components/Books/BorrowBook.vue').default)

const app = new Vue({
    el: '#app',
})