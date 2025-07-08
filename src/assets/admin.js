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

import routes from './admin-routes.js'
import * as helorui from 'helorui'


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
            } = helorui.functions.useAuth();

            return {
                cms,

                user,
                login,
                logout,
            };
        }
    });

    for(const [name, component] of Object.entries(helorui.components)) {
        app.component(name, component);
    }

    app.config.globalProperties.$filters = helorui.filters;

    app.use(router).use(i18n).mount('#admin-app');
}
