<template>
    <div class="form-page form-horizontal">

        <input-wrapper 
            :name="uniqId + 'title'"
            :error="getError('title')"
            label="Titre">
            <div slot="input">
                <input-text 
                    v-model="item.title"
                    :name="uniqId + 'title'"
                    :error="getError('title')"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper>

        <input-wrapper 
            :name="uniqId + 'slug'"
            :error="getError('slug')"
            label="Alias">
            <div slot="input">
                <input-text 
                    v-model="item.slug"
                    :name="uniqId + 'slug'"
                    :error="getError('slug')"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper>

    </div>
</template>

<script>
    import crudFormMixin from 'vue-crud/src/crud/CrudFormMixin.js'
    export default {
        mixins: [crudFormMixin],

        data(){
            return {
                collections: null
            };
        },

        mounted(){
            axios.get('/admin/api/collection').then(response => {
                this.collections = response.data;
            });
        }
    }
</script>
