<template>
    <div>
        
        <input-wrapper-horizontal 
            :name="uniqId + 'title'"
            :error="getError('title')"
            label="Title">
            <div slot="input">
                <input-text 
                    v-model="item.title"
                    :name="uniqId + 'title'"
                    :error="getError('title')"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :name="uniqId + 'seo_title'"
            :error="getError('seo_title')"
            label="Page title">
            <div slot="input">
                <input-text 
                    v-model="item.seo_title"
                    :name="uniqId + 'seo_title'"
                    :error="getError('seo_title')"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :name="uniqId + 'published'"
            :error="getError('published')"
            label="Published">
            <div slot="input">
                <input-checkbox 
                    v-model="item.published"
                    :name="uniqId + 'published'"
                    :error="getError('published')"
                    @input="updated">
                </input-checkbox>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :name="uniqId + 'published_at'"
            :error="getError('published_at')"
            label="Date">
            <div slot="input">
                <input-date 
                    v-model="item.published_at"
                    :name="uniqId + 'published_at'"
                    :error="getError('published_at')"
                    @input="updated">
                </input-date>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :name="uniqId + 'image'"
            :error="getError('image')"
            label="Main media">
            <div slot="input">
                <media-input 
                    v-model="item.image"
                    :multiple="false"
                    :name="uniqId + 'image'"
                    :error="getError('image')"
                    :uri-prefix="uriPrefix"
                    @input="updated">
                </media-input>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :name="uniqId + 'images'"
            :error="getError('images')"
            label="Other medias">
            <div slot="input">
                <media-input 
                    v-model="item.images"
                    :multiple="true"
                    :name="uniqId + 'images'"
                    :error="getError('images')"
                    :uri-prefix="uriPrefix"
                    @input="updated">
                </media-input>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-basic 
            :name="uniqId + 'preview'"
            :error="getError('preview')"
            label="Summary">
            <div slot="input">
                <input-textarea 
                    v-model="item.preview"
                    :name="uniqId + 'preview'"
                    :error="getError('preview')"
                    @input="updated">
                </input-textarea>
            </div>
        </input-wrapper-basic>

        <input-wrapper-basic 
            :name="uniqId + 'content'"
            :error="getError('content')"
            label="Content">
            <div slot="input">
                <input-editor 
                    v-model="item.content"
                    :name="uniqId + 'content'"
                    :error="getError('content')"
                    :css="editorCss"
                    @input="updated">
                </input-editor>
            </div>
        </input-wrapper-basic>

        <input-wrapper-basic 
            :name="uniqId + 'seo_description'"
            :error="getError('seo_description')"
            label="SEO Description">
            <div slot="input">
                <input-textarea 
                    v-model="item.seo_description"
                    :name="uniqId + 'seo_description'"
                    :error="getError('seo_description')"
                    @input="updated">
                </input-textarea>
            </div>
        </input-wrapper-basic>

    </div>
</template>

<script>

    import formMixin from 'vue-crud/src/mixins/FormMixin.js'
    import inputWrapperHorizontal from 'vue-crud/src/inputs-wrappers/InputWrapperHorizontal.vue'
    import inputWrapperBasic from 'vue-crud/src/inputs-wrappers/InputWrapperBasic.vue'
    import inputText from 'vue-crud/src/inputs/InputText.vue'
    import inputDate from 'vue-crud/src/inputs/InputDate.vue'
    import inputTextarea from 'vue-crud/src/inputs/InputTextarea.vue'
    import inputCheckbox from 'vue-crud/src/inputs/InputCheckbox.vue'
    import inputEditor from 'vue-crud/src/inputs/InputEditor.vue'
    
    import mediaInput from '../medias/MediaInput.vue'

    export default {
        mixins: [formMixin],
        components: {
            inputWrapperHorizontal: inputWrapperHorizontal,
            inputWrapperBasic: inputWrapperBasic,
            inputText: inputText,
            inputDate: inputDate,
            inputTextarea: inputTextarea,
            inputCheckbox: inputCheckbox,
            inputEditor: inputEditor,
            mediaInput: mediaInput
        },

        props: {
            uriPrefix: {
                type: String,
                required: false,
                default: ''
            },
            editorCss: {
                type: String,
                required: false,
                default: ''
            }
        }
    }
</script>
