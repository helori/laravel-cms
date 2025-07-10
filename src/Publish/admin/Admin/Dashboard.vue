<template>
    <div class="absolute inset-0 flex flex-col p-8">

        <div>
            <h1 class="text-2xl font-bold mb-4">Bienvenue, {{ user.firstname }} !</h1>
            <p class="text-gray-700 dark:text-gray-300">
                Voici votre interface d'administration.
                Sélectionnez une option dans le menu de gauche pour commencer.
            </p>
        </div>

    </div>
</template>

<script>

import { defineComponent } from "vue"

export default defineComponent({
    props: {
        user: {
            required: true,
        },
    },
})

</script>
