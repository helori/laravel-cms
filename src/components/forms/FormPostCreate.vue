<template>
    <div class="form-post form-horizontal">

        <input-wrapper 
            :name="uniqId + 'title'"
            :error="errors.title"
            label="Titre">
            <div slot="input">
                <input-text 
                    v-model="item.title"
                    :name="uniqId + 'title'"
                    :error="errors.title"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper>

        <input-wrapper 
            :name="uniqId + 'slug'"
            :error="errors.slug"
            label="Alias">
            <div slot="input">
                <input-text 
                    v-model="item.slug"
                    :name="uniqId + 'slug'"
                    :error="errors.slug"
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
