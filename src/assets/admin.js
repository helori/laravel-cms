window._ = require('lodash');
window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');

require('tinymce');
require('tinymce/themes/modern/theme.min');

require('tinymce/plugins/textcolor/plugin.min');
require('tinymce/plugins/advlist/plugin.min');
require('tinymce/plugins/autolink/plugin.min');
require('tinymce/plugins/lists/plugin.min');
require('tinymce/plugins/link/plugin.min');
require('tinymce/plugins/image/plugin.min');
require('tinymce/plugins/charmap/plugin.min');
require('tinymce/plugins/print/plugin.min');
require('tinymce/plugins/preview/plugin.min');
require('tinymce/plugins/anchor/plugin.min');
require('tinymce/plugins/emoticons/plugin.min');
require('tinymce/plugins/searchreplace/plugin.min');
require('tinymce/plugins/visualblocks/plugin.min');
require('tinymce/plugins/code/plugin.min');
require('tinymce/plugins/fullscreen/plugin.min');
require('tinymce/plugins/charmap/plugin.min');
require('tinymce/plugins/insertdatetime/plugin.min');
require('tinymce/plugins/table/plugin.min');
require('tinymce/plugins/contextmenu/plugin.min');
require('tinymce/plugins/paste/plugin.min');

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

Vue.component(
    'input-editor',
    require('./components/laravel-cms/inputs/InputEditor.vue')
);

Vue.component(
    'input-media',
    require('./components/laravel-cms/inputs/InputMedia.vue')
);

Vue.component(
    'input-medias',
    require('./components/laravel-cms/inputs/InputMedias.vue')
);


Vue.component(
    'slide-panel',
    require('./components/laravel-cms/widgets/SlidePanel.vue')
);

const app = new Vue({
    el: '#main-content'
});
