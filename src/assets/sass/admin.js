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

/**
 * Filters
 */
require('./components/laravel-cms/filters/FilterDate.js');
require('./components/laravel-cms/filters/FilterNumber.js');

/**
 * CRUD
 */
Vue.component(
    'crud-table',
    require('./components/laravel-cms/crud/CrudTable.vue')   
);
Vue.component(
    'crud-form',
    require('./components/laravel-cms/crud/CrudForm.vue')
);



/**
 * Managers
 */
Vue.component(
    'medias-manager',
    require('./components/laravel-cms/MediasManager.vue')
);

Vue.component(
    'media-updater',
    require('./components/laravel-cms/MediaUpdater.vue')
);

Vue.component(
    'element-manager',
    require('./components/laravel-cms/ElementManager.vue')
);

Vue.component(
    'element-updater',
    require('./components/laravel-cms/ElementUpdater.vue')
);


/**
 * Inputs
 */
Vue.component(
    'input-wrapper',
    require('./components/laravel-cms/inputs/InputWrapper.vue')
);

Vue.component(
    'input-date',
    require('./components/laravel-cms/inputs/InputDate.vue')
);

Vue.component(
    'input-editor',
    require('./components/laravel-cms/inputs/InputEditor.vue')
);

Vue.component(
    'input-email',
    require('./components/laravel-cms/inputs/InputEmail.vue')
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
    'input-multiselect',
    require('./components/laravel-cms/inputs/InputMultiselect.vue')
);

Vue.component(
    'input-number',
    require('./components/laravel-cms/inputs/InputNumber.vue')
);

Vue.component(
    'input-password',
    require('./components/laravel-cms/inputs/InputPassword.vue')
);

Vue.component(
    'input-phone',
    require('./components/laravel-cms/inputs/InputPhone.vue')
);

Vue.component(
    'input-select',
    require('./components/laravel-cms/inputs/InputSelect.vue')
);

Vue.component(
    'input-text',
    require('./components/laravel-cms/inputs/InputText.vue')
);

Vue.component(
    'input-checkbox',
    require('./components/laravel-cms/inputs/InputCheckbox.vue')
);





/**
 * Forms
 */
Vue.component(
    'form-admin',
    require('./components/laravel-cms/forms/FormAdmin.vue')
);
Vue.component(
    'form-table',
    require('./components/laravel-cms/forms/FormTable.vue')
);
Vue.component(
    'form-table-fields',
    require('./components/laravel-cms/forms/FormTableFields.vue')
);
Vue.component(
    'form-element-create',
    require('./components/laravel-cms/forms/FormElementCreate.vue')
);
Vue.component(
    'form-element-update',
    require('./components/laravel-cms/forms/FormElementUpdate.vue')
);

/**
 * Table Rows
 */
Vue.component(
    'row-admin',
    require('./components/laravel-cms/table-rows/RowAdmin.vue')
);
Vue.component(
    'row-table',
    require('./components/laravel-cms/table-rows/RowTable.vue')
);
Vue.component(
    'row-element',
    require('./components/laravel-cms/table-rows/RowElement.vue')
);


/**
 * Widgets
 */
Vue.component(
    'panel',
    require('./components/laravel-cms/widgets/Panel.vue')
);

Vue.component(
    'slide-panel',
    require('./components/laravel-cms/widgets/SlidePanel.vue')
);


/**
 * Application
 */
const app = new Vue({
    el: '#main-content'
});
