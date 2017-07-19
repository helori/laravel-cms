<template>
    <div class="form-table-fields">

        <panel>
            <div slot="title">Champs de la table</div>

            <div slot="body">

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Ordre</th>
                            <th>Type</th>
                            <th>Nom</th>
                            <th>Titre</th>
                            <th>Défault</th>
                            <th>Création</th>
                            <th>Liste</th>
                            <th class="text-right">
                                <button type="button" 
                                    class="btn btn-primary"
                                    @click="createField()">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(field, idx) in item.fields">
                            <td>
                                <input-text v-model="field.position"></input-text>
                            </td>
                            <td>
                                <input-select 
                                    v-model="field.type" 
                                    :options="field_types"
                                    @input="resetDefault(field)">
                                </input-select>
                            </td>
                            <td>
                                <input-text v-model="field.name" placeholder="Nom du champ"></input-text>
                            </td>
                            <td>
                                <input-text v-model="field.title" placeholder="Titre du champ"></input-text>
                            </td>

                            <td>
                                <input-text
                                    v-if="field.type == 'text'"
                                    v-model="field.default"
                                    :name="'default-' + idx"
                                    placeholder="Default...">
                                </input-text>
                                
                                <input-date 
                                    v-if="field.type == 'date'"
                                    v-model="field.default"
                                    :name="'default-' + idx">
                                </input-date>

                                <input-checkbox 
                                    v-if="field.type == 'checkbox'"
                                    v-model="field.default"
                                    :name="'default-' + idx">
                                </input-checkbox>

                                <input-select
                                    v-if="field.type == 'alias'"
                                    v-model="field.default"
                                    :has-empty="false"
                                    :name="'default-' + idx"
                                    :options="aliasOpts()">
                                </input-select>
                                
                                <div v-if="field.type == 'relation'" class="row narrow">
                                    <div class="col col-sm-6">
                                        <input-select
                                            v-model="field.options.table"
                                            :has-empty="false"
                                            :name="'options-table-' + idx"
                                            :options="relationTablesOpts()">
                                        </input-select>
                                    </div>
                                    <div class="col col-sm-6">
                                        <input-select
                                            v-if="field.options.table"
                                            v-model="field.options.field"
                                            :has-empty="false"
                                            :name="'options-field-' + idx"
                                            :options="relationFieldsOpts(field.options.table)">
                                        </input-select>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <input-checkbox 
                                    v-if="field.type !== 'alias' && field.type !== 'media' && field.type !== 'medias' && field.type !== 'editor'"
                                    v-model="field.create"
                                    :name="'create-' + idx">
                                </input-checkbox>
                            </td>
                            <td>
                                <input-checkbox 
                                    v-if="field.type !== 'alias' && field.type !== 'media' && field.type !== 'medias' && field.type !== 'editor'"
                                    v-model="field.list"
                                    :name="'list-' + idx">
                                </input-checkbox>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-block"
                                    @click="deleteField(idx)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>               

                <div class="text-center">
                    <a class="btn btn-default"
                        href="/admin/table">
                        <i class="fa fa-arrow-left"></i> Retour aux tables
                    </a>
                    <button type="button" 
                        class="btn btn-primary"
                        @click="updated()">
                        <i class="fa fa-save"></i> Enregistrer
                    </button>
                </div>

            </div>
        </panel>

    </div>
</template>

<script>
    import crudFormMixin from 'vue-crud/src/crud/CrudFormMixin.js'
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
                    {value: 'medias', label: 'Fichiers média'},
                    {value: 'relation', label: 'Relation à une autre table'},
                ],
                tables: []
            };
        },

        mounted(){
            axios.get('/admin/api/table').then(response => {
                this.tables = response.data;
            }).catch(response => {

            });
        },

        methods: {
            createField: function(){
                this.item.fields.push({
                    type: 'text'
                });
            },

            deleteField: function(idx){
                this.item.fields.splice(idx, 1);
            },

            resetDefault: function(field){
                field.default = null;
                console.log('resettings', field);
                if(field.type == 'relation'){
                    field.options = {
                        table: '',
                        field: ''
                    };
                    console.log(field.options);
                }
            },

            /*afterRead(){
                for(var i=0; i<this.item.fields.length; ++i){
                    var field = this.item.fields[i];
                    if(field.type == 'relation'){
                        if(!this.item.fields[i].options.table){
                            this.item.fields[i].options.table = '';    
                        }
                        if(!this.item.fields[i].options.field){
                            this.item.fields[i].options.field = '';    
                        }
                    }
                }
            },*/

            aliasOpts(){
                var opts = [];
                for(var i=0; i<this.item.fields.length; ++i){
                    var field = this.item.fields[i];
                    if(field.type == 'text'){
                        opts.push({
                            value: field.name,
                            label: field.title
                        });
                    }
                }
                return opts;
            },

            relationTablesOpts(){
                var opts = [];
                for(var i=0; i<this.tables.length; ++i){
                    var table = this.tables[i];
                    opts.push({
                        value: table.id,
                        label: table.name
                    });
                }
                return opts;
            },

            relationFieldsOpts(tableId){
                var opts = [];
                if(tableId){
                    for(var i=0; i<this.tables.length; ++i){
                        var table = this.tables[i];
                        if(table.id == tableId){
                            for(var i=0; i<table.fields.length; ++i){
                                var field = table.fields[i];
                                opts.push({
                                    value: field.name,
                                    label: field.name
                                });
                            }
                        }
                    }
                }
                return opts;
            }
        }
    }
</script>
