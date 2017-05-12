window._ = require('lodash');
window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');

window.axios = require('axios');
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Vue = require('vue');

Vue.component(
    'media',
    require('./components/laravel-cms/Media.vue')
);

Vue.component(
    'medias-manager',
    require('./components/laravel-cms/MediasManager.vue')
);

Vue.component(
    'tables-manager',
    require('./components/laravel-cms/TablesManager.vue')
);

Vue.component(
    'table-manager',
    require('./components/laravel-cms/TableManager.vue')
);

Vue.component(
    'input-text',
    require('./components/laravel-cms/inputs/InputText.vue')
);

Vue.component(
    'input-date',
    require('./components/laravel-cms/inputs/InputDate.vue')
);

Vue.component(
    'input-checkbox',
    require('./components/laravel-cms/inputs/InputCheckbox.vue')
);

const app = new Vue({
    el: '#main-content'
});
