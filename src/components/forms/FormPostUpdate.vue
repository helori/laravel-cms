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

        <input-wrapper 
            :name="uniqId + 'author'"
            :error="errors.author"
            label="Auteur">
            <div slot="input">
                <input-text 
                    v-model="item.author"
                    :name="uniqId + 'author'"
                    :error="errors.author"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper>

        <input-wrapper 
            :name="uniqId + 'published'"
            :error="errors.published"
            label="Publié">
            <div slot="input">
                <input-checkbox 
                    v-model="item.published"
                    :name="uniqId + 'published'"
                    :error="errors.published"
                    @input="updated">
                </input-checkbox>
            </div>
        </input-wrapper>

        <input-wrapper 
            :name="uniqId + 'date'"
            :error="errors.date"
            label="Date">
            <div slot="input">
                <input-date 
                    v-model="item.date"
                    :name="uniqId + 'date'"
                    :error="errors.date"
                    @input="updated">
                </input-date>
            </div>
        </input-wrapper>

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
            :name="uniqId + 'preview'"
            :error="getError('preview')"
            label="Résumé">
            <div slot="input">
                <input-textarea 
                    v-model="item.preview"
                    :name="uniqId + 'preview'"
                    :error="getError('preview')"
                    @input="updated">
                </input-textarea>
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
        mixins: [crudFormMixin]
    }
</script>
