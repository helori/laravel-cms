<template>
    <div class="form-field form-horizontal">

        <input-wrapper 
            :name="uniqId + 'position'"
            :error="errors.position"
            label="Position">
            <div slot="input">
                <input-text 
                    v-model="item.position"
                    :name="uniqId + 'position'"
                    :error="errors.position"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper>

        <input-wrapper 
            :name="uniqId + 'type'"
            :error="errors.type"
            label="Type">
            <div slot="input">
                <input-select 
                    v-model="item.type"
                    :name="uniqId + 'type'"
                    :options="field_types"
                    :error="errors.type"
                    @input="resetDefault(); updated()">
                </input-select>
            </div>
        </input-wrapper>

        <input-wrapper 
            :name="uniqId + 'name'"
            :error="errors.name"
            label="Nom">
            <div slot="input">
                <input-text 
                    v-model="item.name"
                    :name="uniqId + 'name'"
                    :error="errors.name"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper>

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
            :name="uniqId + 'default'"
            :error="errors.default"
            label="Default">
            <div slot="input">

                <input-text 
                    v-if="item.type == 'text'"
                    v-model="item.default"
                    :name="uniqId + 'default'"
                    :error="errors.default"
                    @input="updated">
                </input-text>

                <input-date 
                    v-if="item.type == 'date'"
                    v-model="item.default"
                    :name="uniqId + 'default'"
                    :error="errors.default"
                    @input="updated">
                </input-date>

                <input-checkbox 
                    v-if="item.type == 'checkbox'"
                    v-model="item.default"
                    :name="uniqId + 'default'"
                    :error="errors.default"
                    @input="updated">
                </input-checkbox>

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
                field_types: [
                    {value: 'text', label: 'Champ de texte'},
                    {value: 'select', label: 'Liste déroulante'},
                    {value: 'checkbox', label: 'Case à cocher'},
                    {value: 'url', label: 'URL'},
                    {value: 'email', label: 'Email'},
                    {value: 'date', label: 'Date'},
                    {value: 'alias', label: 'Alias'},
                    {value: 'editor', label: 'Éditeur de texte'},
                    {value: 'textarea', label: 'Zone de texte (multi-lignes)'},
                    {value: 'media', label: 'Fichier média'},
                    {value: 'medias', label: 'Fichiers média'}
                ]
            };
        },

        methods: {
            resetDefault: function(){
                this.item.default = null;
            }
        }
    }
</script>
