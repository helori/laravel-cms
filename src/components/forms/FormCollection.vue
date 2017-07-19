<template>
    <div class="form-collection form-horizontal">

        <input-wrapper 
            :name="uniqId + 'title'"
            :error="errors.title"
            label="Nom">
            <div slot="input">
                <input-text
                    v-model="item.title"
                    :name="uniqId + 'title'"
                    :error="errors.title"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper>  

    </div>
</template>

<script>
    import crudFormMixin from 'vue-crud/src/crud/CrudFormMixin.js'
    export default {
        mixins: [crudFormMixin]
    }
</script>
