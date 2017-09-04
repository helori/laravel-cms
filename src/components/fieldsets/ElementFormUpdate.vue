<template>
    <div>

        <div v-for="field in fieldset.fields" v-if="field.use_when_update">

            <input-wrapper-horizontal 
                :name="uniqId + field.name"
                :error="getError(field.name)"
                :label="field.title">
                <div slot="input">

                    <media-input
                        v-if="field.type == 'media'"
                        v-model="item[field.name]"
                        :name="uniqId + field.name"
                        :error="getError(field.name)"
                        :multiple="false"
                        :uri-prefix="uriPrefix"
                        @input="updated">
                    </media-input>

                    <component
                        v-else
                        :is="'input-' + field.type"
                        v-model="item[field.name]"
                        :name="uniqId + field.name"
                        :error="getError(field.name)"
                        @input="updated">
                    </component>

                </div>
            </input-wrapper-horizontal>

        </div>

    </div>
</template>

<script>
    
    import formMixin from 'vue-crud/src/mixins/FormMixin.js'
    
    import inputWrapperHorizontal from 'vue-crud/src/inputs-wrappers/InputWrapperHorizontal.vue'

    import inputText from 'vue-crud/src/inputs/InputText.vue'
    import inputEmail from 'vue-crud/src/inputs/InputEmail.vue'
    import inputSelect from 'vue-crud/src/inputs/InputSelect.vue'
    import inputTextarea from 'vue-crud/src/inputs/InputTextarea.vue'
    import inputEditor from 'vue-crud/src/inputs/InputEditor.vue'
    import inputCheckbox from 'vue-crud/src/inputs/InputCheckbox.vue'

    import mediaInput from '../medias/MediaInput.vue'

    export default {
        mixins: [formMixin],
        components: {
            inputWrapperHorizontal: inputWrapperHorizontal,

            inputText: inputText,
            inputEmail: inputEmail,
            inputSelect: inputSelect,
            inputTextarea: inputTextarea,
            inputEditor: inputEditor,
            inputCheckbox: inputCheckbox,

            mediaInput: mediaInput
        },
        props: {
            fieldset: {
                required: true
            },
            uriPrefix: {
                type: String,
                required: false,
                default: ''
            }
        }
    }
</script>
