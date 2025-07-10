<template>

    <div class="absolute inset-0 flex flex-col bg-gray-100 dark:bg-gray-700 dark:text-white">

        <div class="absolute z-20 top-0 inset-x-0 h-16 bg-white">
            <main-menu :user="user" />
        </div>

        <div class="absolute inset-x-0 bottom-0 top-16">

            <aside class="absolute inset-y-0 left-0 w-[300px] overflow-y-scroll py-8 flex flex-col bg-gray-200 dark:bg-gray-900">

                <!--router-link
                    :to="{name: 'admin-dashboard'}"
                    active-class="font-bold bg-gray-100 dark:bg-gray-700">
                    Accueil
                </router-link-->

                <a href="/"
                    target="_blank"
                    class="flex items-center gap-2">
                    <div class="shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                    </div>
                    <div>Voir le site</div>
                </a>

                <template v-for="group in cms.menu">

                    <div class="menu-group">
                        {{ group.title }}
                    </div>

                    <template v-for="m in group.menus">

                        <router-link
                            v-if="m.type === 'resource'"
                            :to="{name: m.name}"
                            active-class="font-bold bg-gray-100 dark:bg-gray-700">
                            {{ m.title }}
                        </router-link>

                    </template>

                </template>

            </aside>

            <main class="absolute inset-y-0 right-0 left-[300px] overflow-y-scroll">
                <router-view
                    :user="user"
                    :key="$route.fullPath">
                </router-view>
            </main>

        </div>

    </div>

</template>

<script>

import { defineComponent } from 'vue'
import MainMenu from './MainMenu'

export default defineComponent({

    components: {
        MainMenu,
    },

    props: {
        user: {
            required: true,
        },
        cms: {
            required: true,
        },
    },
})
</script>
