<template>
    <div>
        
        <input-wrapper-horizontal 
            :title="uniqId + 'short_title'"
            :error="getError('short_title')"
            label="Short title"
            help="Titre affiché dans le menu principal du site">
            <div slot="input">
                <input-text
                    v-model="item.short_title"
                    :title="uniqId + 'short_title'"
                    :error="getError('short_title')"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :title="uniqId + 'seo_title'"
            :error="getError('seo_title')"
            label="SEO title"
            help="Titre dans les résultats Google">
            <div slot="input">
                <input-text
                    v-model="item.seo_title"
                    :title="uniqId + 'seo_title'"
                    :error="getError('seo_title')"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :title="uniqId + 'title'"
            :error="getError('title')"
            label="Title"
            help="Titre affiché dans la page">
            <div slot="input">
                <input-text
                    v-model="item.title"
                    :title="uniqId + 'title'"
                    :error="getError('title')"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :title="uniqId + 'subtitle'"
            :error="getError('subtitle')"
            label="Subtitle"
            help="Sous-titre affiché dans la page">
            <div slot="input">
                <input-text
                    v-model="item.subtitle"
                    :title="uniqId + 'subtitle'"
                    :error="getError('subtitle')"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :title="uniqId + 'position'"
            :error="getError('position')"
            label="Position">
            <div slot="input">
                <input-number
                    v-model="item.position"
                    :title="uniqId + 'position'"
                    :error="getError('position')"
                    @input="updated">
                </input-number>
            </div>
        </input-wrapper-horizontal>

    </div>
</template>

<script>
    
    import formMixin from 'vue-crud/src/mixins/FormMixin.js'
    import inputWrapperHorizontal from 'vue-crud/src/inputs-wrappers/InputWrapperHorizontal.vue'
    import inputText from 'vue-crud/src/inputs/InputText.vue'
    import inputNumber from 'vue-crud/src/inputs/InputNumber.vue'

    export default {
        mixins: [formMixin],
        components: {
            inputWrapperHorizontal: inputWrapperHorizontal,
            inputText: inputText,
            inputNumber: inputNumber
        },

        props: {
            uriPrefix: {
                type: String,
                required: false,
                default: ''
            }
        }
    }
</script>
