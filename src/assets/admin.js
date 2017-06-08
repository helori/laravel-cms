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

var components_path = '../../../vendor/helori/laravel-cms/src/components/';
//var components_path = './components/laravel-cms/';

/**
 * Filters
 */
//require(components_path + 'filters/FilterDate.js');
require(components_path + 'filters/FilterDate.js');
require(components_path + 'filters/FilterNumber.js');

/**
 * CRUD
 */
Vue.component(
    'crud-table',
    require(components_path + 'crud/CrudTable.vue')   
);
Vue.component(
    'crud-form',
    require(components_path + 'crud/CrudForm.vue')
);



/**
 * Managers
 */
Vue.component(
    'medias-manager',
    require(components_path + 'MediasManager.vue')
);

Vue.component(
    'media-updater',
    require(components_path + 'MediaUpdater.vue')
);

Vue.component(
    'element-manager',
    require(components_path + 'ElementManager.vue')
);

Vue.component(
    'element-updater',
    require(components_path + 'ElementUpdater.vue')
);


/**
 * Inputs
 */
Vue.component(
    'input-wrapper',
    require(components_path + 'inputs/InputWrapper.vue')
);

Vue.component(
    'input-date',
    require(components_path + 'inputs/InputDate.vue')
);

Vue.component(
    'input-editor',
    require(components_path + 'inputs/InputEditor.vue')
);

Vue.component(
    'input-email',
    require(components_path + 'inputs/InputEmail.vue')
);

Vue.component(
    'input-media',
    require(components_path + 'inputs/InputMedia.vue')
);

Vue.component(
    'input-medias',
    require(components_path + 'inputs/InputMedias.vue')
);

Vue.component(
    'input-multiselect',
    require(components_path + 'inputs/InputMultiselect.vue')
);

Vue.component(
    'input-number',
    require(components_path + 'inputs/InputNumber.vue')
);

Vue.component(
    'input-password',
    require(components_path + 'inputs/InputPassword.vue')
);

Vue.component(
    'input-phone',
    require(components_path + 'inputs/InputPhone.vue')
);

Vue.component(
    'input-select',
    require(components_path + 'inputs/InputSelect.vue')
);

Vue.component(
    'input-text',
    require(components_path + 'inputs/InputText.vue')
);

Vue.component(
    'input-checkbox',
    require(components_path + 'inputs/InputCheckbox.vue')
);





/**
 * Forms
 */
Vue.component(
    'form-admin',
    require(components_path + 'forms/FormAdmin.vue')
);
Vue.component(
    'form-table',
    require(components_path + 'forms/FormTable.vue')
);
Vue.component(
    'form-table-fields',
    require(components_path + 'forms/FormTableFields.vue')
);
Vue.component(
    'form-element-create',
    require(components_path + 'forms/FormElementCreate.vue')
);
Vue.component(
    'form-element-update',
    require(components_path + 'forms/FormElementUpdate.vue')
);

/**
 * Table Rows
 */
Vue.component(
    'row-admin',
    require(components_path + 'table-rows/RowAdmin.vue')
);
Vue.component(
    'row-table',
    require(components_path + 'table-rows/RowTable.vue')
);
Vue.component(
    'row-element',
    require(components_path + 'table-rows/RowElement.vue')
);


/**
 * Widgets
 */
Vue.component(
    'panel',
    require(components_path + 'widgets/Panel.vue')
);

Vue.component(
    'slide-panel',
    require(components_path + 'widgets/SlidePanel.vue')
);


/**
 * Application
 */
const app = new Vue({
    el: '#main-content'
});
