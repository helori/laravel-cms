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

        <input-wrapper 
            :name="uniqId + 'published'"
            :error="getError('titpublishedle')"
            label="Publié">
            <div slot="input">
                <input-checkbox 
                    v-model="item.published"
                    :name="uniqId + 'published'"
                    :error="getError('published')"
                    @input="updated">
                </input-checkbox>
            </div>
        </input-wrapper>
        
        <div v-if="collections">
            <input-wrapper 
                :name="uniqId + 'collections'"
                :error="getError('collections')"
                label="Collections">
                <div slot="input">
                    <input-multiselect 
                        v-model="item.collections"
                        :name="uniqId + 'collections'"
                        :options="collections"
                        option-label-key="title"
                        option-value-key="id"
                        :error="getError('collections')"
                        @input="updated">
                    </input-multiselect>
                </div>
            </input-wrapper>
        </div>

        <input-wrapper 
            :name="uniqId + 'image'"
            :error="getError('image')"
            label="Image principale">
            <div slot="input">
                <input-media 
                    v-model="item.image"
                    :name="uniqId + 'image'"
                    :error="getError('image')"
                    @input="updated">
                </input-media>
            </div>
        </input-wrapper>

        <input-wrapper 
            :name="uniqId + 'images'"
            :error="getError('images')"
            label="Images">
            <div slot="input">
                <input-medias 
                    v-model="item.images"
                    :name="uniqId + 'images'"
                    :error="getError('images')"
                    @input="updated">
                </input-medias>
            </div>
        </input-wrapper>

        <input-wrapper 
            :name="uniqId + 'content'"
            :error="getError('content')"
            label="Contenu">
            <div slot="input">
                <input-editor 
                    v-model="item.content"
                    :name="uniqId + 'content'"
                    :error="getError('content')"
                    @input="updated">
                </input-editor>
            </div>
        </input-wrapper>

    </div>
</template>

<script>
    import crudFormMixin from '../crud/CrudFormMixin.js'
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
        },

        methods: {
            setItem(itemOrg){
                var item = _.clone(this.itemOrg);
                item.collections = _.map(this.itemOrg.collections, 'id');
                this.item = item;
                this.afterRead();
            }
        }
    }
</script>
