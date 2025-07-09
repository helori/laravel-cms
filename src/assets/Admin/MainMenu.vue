<template>

    <header class="border-b bg-white border-gray-300 dark:border-gray-700 dark:bg-gray-950">
        <div class="h-16 mx-auto px-2 md:px-4 flex items-center gap-2 md:gap-4">

            <div class="w-32 md:w-40 md:text-sm text-left uppercase font-medium">
                <div @click="goHome" class="cursor-pointer leading-[1rem]">
                    <div class="font-bold">
                        {{ appName }}
                    </div>
                    <div class="text-xs">
                        Administration
                    </div>
                </div>
            </div>

            <div class="flex-grow"></div>

            <locale />
            
            <dark-mode />

            <div class="relative">
                <dropdown>
                    <template #trigger>
                        <button type="button"
                            class="inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md focus:outline-none transition text-gray-500 bg-white hover:text-gray-700 dark:text-gray-400 dark:bg-gray-950 dark:hover:text-gray-200">

                            <div class="text-right">
                                <div class="text-gray-900 dark:text-white font-semibold">
                                    {{ user.firstname }} {{ user.lastname }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ user.email }}
                                </div>
                            </div>

                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>

                        </button>
                    </template>

                    <template #content>

                        <a @click="logoutAndRedirect"
                            class="dropdown-link text-red-600 dark:text-red-400">
                            <div>
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
                                </svg>
                            </div>
                            <div>Déconnexion</div>
                        </a>

                    </template>
                </dropdown>
            </div>

        </div>

    </header>

</template>

<script>

import { defineComponent, ref, inject } from "vue"
import { useRouter } from 'vue-router'
import Locale from'./Locale'
import DarkMode from'./DarkMode'

export default defineComponent({

    components: {
        Locale,
        DarkMode
    },

    props: {
        user: {
            required: true,
        },
    },

    setup(props, { emit })
    {
        const appName = ref(import.meta.env.VITE_APP_NAME);
        const router = useRouter();

        function goHome()
        {
            router.push({
                name: 'admin-dashboard'
            })
        }

        const logout = inject('logout');

        function logoutAndRedirect()
        {
            logout().then(r => {
                window.location.reload();
            });
        }

        return {
            appName,
            goHome,
            logoutAndRedirect,
        }
    }
})
</script>
