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

        <div class="alert alert-danger" v-if="getGlobalError()">
            {{ getGlobalError() }}
        </div>

    </div>
</template>

<script>
    
    import formMixin from 'vue-crud/src/mixins/FormMixin.js'
    
    import inputWrapperHorizontal from 'vue-crud/src/inputs-wrappers/InputWrapperHorizontal.vue'
    import inputText from 'vue-crud/src/inputs/InputText.vue'

    export default {
        mixins: [formMixin],
        components: {
            inputWrapperHorizontal: inputWrapperHorizontal,
            inputText: inputText
        }
    }
</script>
