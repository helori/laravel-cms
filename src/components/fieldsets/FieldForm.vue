<template>
    <div v-if="item">

        <input-wrapper-horizontal 
            :name="uniqId + 'title'"
            :error="getError('title')"
            label="Display title">
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
            :name="uniqId + 'type'"
            :error="getError('type')"
            label="Field type">
            <div slot="input">
                <input-select
                    v-model="item.type"
                    :name="uniqId + 'type'"
                    :error="getError('type')"
                    :options="[
                        {value: 'text', label: 'Text'},
                        {value: 'url', label: 'URL'},
                        {value: 'email', label: 'Email'},
                        {value: 'phone', label: 'Phone'},
                        {value: 'password', label: 'Password'},
                        {value: 'textarea', label: 'Textarea'},
                        {value: 'editor', label: 'Editor'},
                        {value: 'checkbox', label: 'Checkbox'},
                        {value: 'media', label: 'Media'}
                    ]"
                    @input="updated">
                </input-select>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :name="uniqId + 'default'"
            :error="getError('default')"
            label="Default value">
            <div slot="input">
                <input-text
                    v-model="item.default"
                    :name="uniqId + 'default'"
                    :error="getError('default')"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :name="uniqId + 'use_when_create'"
            :error="getError('use_when_create')"
            label="Show when creating">
            <div slot="input">
                <input-checkbox
                    v-model="item.use_when_create"
                    :name="uniqId + 'use_when_create'"
                    :error="getError('use_when_create')"
                    @input="updated">
                </input-checkbox>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :name="uniqId + 'use_when_update'"
            :error="getError('use_when_update')"
            label="Show when updating">
            <div slot="input">
                <input-checkbox
                    v-model="item.use_when_update"
                    :name="uniqId + 'use_when_update'"
                    :error="getError('use_when_update')"
                    @input="updated">
                </input-checkbox>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :name="uniqId + 'show_in_list'"
            :error="getError('show_in_list')"
            label="Display in list">
            <div slot="input">
                <input-checkbox
                    v-model="item.show_in_list"
                    :name="uniqId + 'show_in_list'"
                    :error="getError('show_in_list')"
                    @input="updated">
                </input-checkbox>
            </div>
        </input-wrapper-horizontal>

        <div class="alert alert-danger" v-if="getGlobalError()">
            {{ getGlobalError() }}
        </div>

    </div>
</template>

<script>
    
    import formMixin from 'vue-crud/src/mixins/FormMixin.js'
    
    import inputWrapperHorizontal from 'vue-crud/src/inputs-wrappers/InputWrapperHorizontal.vue'
    import inputText from 'vue-crud/src/inputs/InputText.vue'
    import inputSelect from 'vue-crud/src/inputs/InputSelect.vue'
    import inputCheckbox from 'vue-crud/src/inputs/InputCheckbox.vue'

    export default {
        mixins: [formMixin],
        components: {
            inputWrapperHorizontal: inputWrapperHorizontal,
            inputText: inputText,
            inputSelect: inputSelect,
            inputCheckbox: inputCheckbox
        }
    }
</script>
