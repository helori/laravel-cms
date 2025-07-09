<template>

    <!-- https://flagicons.lipis.dev/ -->
    <div v-if="locales.length > 1" 
        class="flex gap-1 items-center flex-nowrap">

        <div v-for="l in locales"
            class="cursor-pointer w-6 text-center text-base"
            :class="{
                'underline font-bold': $i18n.locale === l,
                'hover:text-black': $i18n.locale !== l,
            }"
            @click="setLocale(l)">
            {{ $filters.strtoupper(l) }}
        </div>

    </div>

</template>

<script>

import { defineComponent } from "vue"
import { functions } from 'helorui'

export default defineComponent({

    setup()
    {
        const {
            locales,
            setLocale,
            locale,
            backendLocale,
        } = functions.useLocale();

        return {
            locales,
            setLocale,
            locale,
            backendLocale,
        };
    },
})
</script>