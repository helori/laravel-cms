<template>
    <div class="form-field form-horizontal">

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
    import crudFormMixin from '../crud/CrudFormMixin.js'
    export default {
        mixins: [crudFormMixin]
    }
</script>
