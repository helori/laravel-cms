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



require('../components/filters/FilterDate.js');
require('../components/filters/FilterNumber.js');

/**
 * CRUD
 */
Vue.component(
    'crud-table',
    require('../components/crud/CrudTable.vue')   
);
Vue.component(
    'crud-form',
    require('../components/crud/CrudForm.vue')
);



/**
 * Managers
 */
Vue.component(
    'medias-manager',
    require('../components/MediasManager.vue')
);

Vue.component(
    'media-updater',
    require('../components/MediaUpdater.vue')
);

Vue.component(
    'element-manager',
    require('../components/ElementManager.vue')
);

Vue.component(
    'element-updater',
    require('../components/ElementUpdater.vue')
);


/**
 * Inputs
 */
Vue.component(
    'input-wrapper',
    require('../components/inputs/InputWrapper.vue')
);

Vue.component(
    'input-checkbox',
    require('../components/inputs/InputCheckbox.vue')
);

Vue.component(
    'input-date',
    require('../components/inputs/InputDate.vue')
);

Vue.component(
    'input-editor',
    require('../components/inputs/InputEditor.vue')
);

Vue.component(
    'input-email',
    require('../components/inputs/InputEmail.vue')
);

Vue.component(
    'input-media',
    require('../components/inputs/InputMedia.vue')
);

Vue.component(
    'input-medias',
    require('../components/inputs/InputMedias.vue')
);

Vue.component(
    'input-multiselect',
    require('../components/inputs/InputMultiselect.vue')
);

Vue.component(
    'input-number',
    require('../components/inputs/InputNumber.vue')
);

Vue.component(
    'input-password',
    require('../components/inputs/InputPassword.vue')
);

Vue.component(
    'input-phone',
    require('../components/inputs/InputPhone.vue')
);

Vue.component(
    'input-select',
    require('../components/inputs/InputSelect.vue')
);

Vue.component(
    'input-text',
    require('../components/inputs/InputText.vue')
);

Vue.component(
    'input-textarea',
    require('../components/inputs/InputTextarea.vue')
);





/**
 * Forms
 */
Vue.component(
    'form-admin',
    require('../components/forms/FormAdmin.vue')
);
Vue.component(
    'form-table',
    require('../components/forms/FormTable.vue')
);
Vue.component(
    'form-table-fields',
    require('../components/forms/FormTableFields.vue')
);
Vue.component(
    'form-element-create',
    require('../components/forms/FormElementCreate.vue')
);
Vue.component(
    'form-element-update',
    require('../components/forms/FormElementUpdate.vue')
);

/**
 * Table Rows
 */
Vue.component(
    'row-admin',
    require('../components/table-rows/RowAdmin.vue')
);
Vue.component(
    'row-table',
    require('../components/table-rows/RowTable.vue')
);
Vue.component(
    'row-element',
    require('../components/table-rows/RowElement.vue')
);


/**
 * Widgets
 */
Vue.component(
    'panel',
    require('../components/widgets/Panel.vue')
);

Vue.component(
    'slide-panel',
    require('../components/widgets/SlidePanel.vue')
);

/**
 * Application
 */
const app = new Vue({
    el: '#main-content'
});
