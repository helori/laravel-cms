window._ = require('lodash');
window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');

window.axios = require('axios');
window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.Laravel.csrfToken;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Vue = require('vue');

Vue.component(
    'round-shape',
    require('./components/Round.vue')
);

const app = new Vue({
    el: '#main-wrapper'
});

setTimeout(function(){
	$('#social-share').addClass('loaded');
}, 100);