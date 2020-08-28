require('./bootstrap')

window.SlimSelect = require('slim-select')

window.Vue = require('vue')

Vue.mixin({
    methods: {
        route: route // for Ziggy package
    },
})

import VueContentPlaceholders from 'vue-content-placeholders'

Vue.use(VueContentPlaceholders)

Vue.component('library-navigation',     require('./components/Library/LibraryNavigation.vue').default)

const app = new Vue({
    el: '#app',
})