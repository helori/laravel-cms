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
            :name="uniqId + 'single'"
            :error="getError('single')"
            label="Single element">
            <div slot="input">
                <input-checkbox
                    v-model="item.single"
                    :name="uniqId + 'single'"
                    :error="getError('single')"
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
    
    import InputWrapperHorizontal from 'vue-crud/src/inputs-wrappers/InputWrapperHorizontal.vue'
    import InputText from 'vue-crud/src/inputs/InputText.vue'
    import InputCheckbox from 'vue-crud/src/inputs/InputCheckbox.vue'

    export default {
        mixins: [formMixin],
        components: {
            InputWrapperHorizontal,
            InputText,
            InputCheckbox,
        }
    }
</script>
