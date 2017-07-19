<template>
    <div class="form-page form-horizontal">

        <input-wrapper-horizontal 
            :name="uniqId + 'published'"
            :error="getError('published')"
            label="Page publiée">
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
            :name="uniqId + 'menu'"
            :error="getError('menu')"
            label="Nom du menu"
            help="Affiché dans les menus du site. Faible incidence sur le référencement : faire court !">
            <div slot="input">
                <input-text 
                    v-model="item.menu"
                    :name="uniqId + 'menu'"
                    :error="getError('menu')"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :name="uniqId + 'seo_title'"
            :error="getError('seo_title')"
            label="Titre de l'onglet"
            help="C'est aussi le titre affiché en bleu dans les résultats Google (environ 60 caractères).">
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
            :name="uniqId + 'slug'"
            :error="getError('slug')"
            label="Alias"
            help="Utilisé dans l'URL de la page. Utiliser des mots-clés en lien avec le titre de l'onglet.">
            <div slot="input">
                <input-text 
                    v-model="item.slug"
                    :name="uniqId + 'slug'"
                    :error="getError('slug')"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :name="uniqId + 'title'"
            :error="getError('title')"
            label="Titre affiché sur la page"
            help="Titre principal de la page.">
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
            :name="uniqId + 'subtitle'"
            :error="getError('subtitle')"
            label="Sous-titre"
            help="Affiché selon le design de la page.">
            <div slot="input">
                <input-text 
                    v-model="item.subtitle"
                    :name="uniqId + 'subtitle'"
                    :error="getError('subtitle')"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :name="uniqId + 'seo_description'"
            :error="getError('seo_description')"
            label="Description courte"
            help="Affichée dans le résultats Google (environ 160 caractères)">
            <div slot="input">
                <input-textarea
                    v-model="item.seo_description"
                    :name="uniqId + 'seo_description'"
                    :error="getError('seo_description')"
                    @input="updated">
                </input-textarea>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :name="uniqId + 'image'"
            :error="getError('image')"
            label="Image principale"
            help="Image ou vidéo utilisée lors du partage de la page sur les réseaux sociaux.">
            <div slot="input">
                <input-media 
                    v-model="item.image"
                    :name="uniqId + 'image'"
                    :error="getError('image')"
                    @input="updated">
                </input-media>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
            :name="uniqId + 'images'"
            :error="getError('images')"
            label="Images"
            help="Utilisées comme une gallerie photos dans la page.">
            <div slot="input">
                <input-medias 
                    v-model="item.images"
                    :name="uniqId + 'images'"
                    :error="getError('images')"
                    @input="updated">
                </input-medias>
            </div>
        </input-wrapper-horizontal>

        <input-wrapper-horizontal 
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
        </input-wrapper-horizontal>

        <div v-if="collections">
            <input-wrapper-horizontal 
                :name="uniqId + 'collections'"
                :error="getError('collections')"
                label="Collections"
                help="Listes d'éléments associés à la page. Il peut s'agir d'une liste d'articles, d'un défilant, ...">
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
            </input-wrapper-horizontal>
        </div>

        <div v-if="tags">
            <input-wrapper-horizontal 
                :name="uniqId + 'tags'"
                :error="getError('tags')"
                label="Tags"
                help="">
                <div slot="input">
                    <input-multiselect 
                        v-model="item.tags"
                        :name="uniqId + 'tags'"
                        :options="tags"
                        option-label-key="title"
                        option-value-key="id"
                        :error="getError('tags')"
                        @input="updated">
                    </input-multiselect>
                </div>
            </input-wrapper-horizontal>
        </div>

    </div>
</template>

<script>
    import crudFormMixin from 'vue-crud/src/crud/CrudFormMixin.js'
    export default {
        mixins: [crudFormMixin],

        data(){
            return {
                collections: null,
                tags: null
            };
        },

        mounted(){
            axios.get('/admin/api/collection').then(response => {
                this.collections = response.data.data;
            });
            axios.get('/admin/api/tag').then(response => {
                this.tags = response.data.data;
            });
        },

        methods: {
            setItem(itemOrg){
                var item = _.clone(this.itemOrg);
                item.collections = _.map(this.itemOrg.collections, 'id');
                item.tags = _.map(this.itemOrg.tags, 'id');
                this.item = item;
                this.afterRead();
            }
        }
    }
</script>
