import axios from 'axios'
window.axios = axios;
window.axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;
window.axios.defaults.headers.common = {
    'X-Requested-With': 'XMLHttpRequest',
    'accept': 'application/json',
};

import resolveConfig from 'tailwindcss/resolveConfig'
import tailwindConfig from '../../tailwind.config.js'
window.tailwindcss = resolveConfig(tailwindConfig)

import { createApp, ref } from 'vue'
import { createRouter, createWebHashHistory } from 'vue-router'
import { createI18n } from 'vue-i18n'
import en from '../../lang/en.json'
import fr from '../../lang/fr.json'

import { useAuth } from './Library/Functions/useAuth'
import routes from './admin-routes.js'
import filters from './Library/filters.js'

import BooleanIcon from './Library/Components/BooleanIcon'
import Alert from './Library/Components/Alert'
import DialogForm from './Library/Components/DialogForm'
import DialogModal from './Library/Components/DialogModal'
import Drawer from './Library/Components/Drawer'
import Dropdown from './Library/Components/Dropdown'
import Collapse from './Library/Components/Collapse'
import ListFooter from './Library/Components/ListFooter'
import ListHeader from './Library/Components/ListHeader'
import ListPagination from './Library/Components/ListPagination'
import Loader from './Library/Components/Loader'
import Modal from './Library/Components/Modal'
import RequestButton from './Library/Components/RequestButton'
import RequestError from './Library/Components/RequestError'
import RequestState from './Library/Components/RequestState'
import RequestStatus from './Library/Components/RequestStatus'
import TableSortLabel from './Library/Components/TableSortLabel'
import InputDate from './Library/Components/InputDate'
import InputFile from './Library/Components/InputFile'
import InputImage from './Library/Components/InputImage'
import InputImages from './Library/Components/InputImages'
import InputMultiselect from './Library/Components/InputMultiselect'
import LabelValue from './Library/Components/LabelValue'
import Editor from './Library/Components/Editor'
import GenericForm from './Library/Components/GenericForm'
import GenericTable from './Library/Components/GenericTable'
import GenericSingleton from './Library/Components/GenericSingleton'


if(document.getElementById("admin-app"))
{
    const router = createRouter({
        history: createWebHashHistory(),
        routes: routes,
    })

    const i18n = createI18n({
        legacy: false,
        locale: window.laravelLocale,
        fallbackLocale: 'en',
        fallbackWarn: false,
        missingWarn: false,
        messages: {
            en: en,
            fr: fr,
        },
    })

    const app = createApp({
        setup()
        {
            const cms = ref(window.cms);

            const {
                user,
                login,
                logout,
            } = useAuth();

            return {
                cms,

                user,
                login,
                logout,
            };
        }
    });

    app.component('boolean-icon', BooleanIcon);
    app.component('alert', Alert);
    app.component('dialog-form', DialogForm);
    app.component('dialog-modal', DialogModal);
    app.component('drawer', Drawer);
    app.component('dropdown', Dropdown);
    app.component('collapse', Collapse);
    app.component('list-footer', ListFooter);
    app.component('list-header', ListHeader);
    app.component('list-pagination', ListPagination);
    app.component('loader', Loader);
    app.component('modal', Modal);
    app.component('request-button', RequestButton);
    app.component('request-error', RequestError);
    app.component('request-state', RequestState);
    app.component('request-status', RequestStatus);
    app.component('table-sort-label', TableSortLabel);
    app.component('input-date', InputDate);
    app.component('input-file', InputFile);
    app.component('input-image', InputImage);
    app.component('input-images', InputImages);
    app.component('input-multiselect', InputMultiselect);
    app.component('label-value', LabelValue);
    app.component('editor', Editor);
    app.component('generic-form', GenericForm);
    app.component('generic-table', GenericTable);
    app.component('generic-singleton', GenericSingleton);

    app.config.globalProperties.$filters = filters;

    app.use(router).use(i18n).mount('#admin-app');
}
