<style scoped>
    .tables-manager{
        position: absolute;
        top: 50px; bottom: 0;
        left: 0; right: 0;
        width: 100%;
    }
    .tables-manager .left{
        z-index: 1;
        position: absolute;
        top: 0; bottom: 0; left: 0;
        width: 500px; height: 100%;
        padding: 30px;
        overflow-x: hidden;
        overflow-y: scroll;
        box-shadow: 1px 0 4px rgba(0, 0, 0, 0.5);
    }
    .tables-manager .right{
        position: absolute;
        top: 0; bottom: 0; 
        left: 500px; right: 0;
        height: 100%;
        padding: 30px;
        overflow-x: hidden;
        overflow-y: scroll;
        background: #666;
    }

    .row.narrow{
        margin-left: -2.5px;
        margin-right: -2.5px;
    }
    .row.narrow .col{
        padding-left: 2.5px;
        padding-right: 2.5px;
    }

    .tables-manager h1{
        margin: 0 0 15px 0;
        font-size: 30px;
        font-weight: 400;
    }
    .tables-manager h2{
        margin: 0 0 15px 0;
        font-size: 16px;
        font-weight: 500;
    }
    .tables-manager .hint{
        margin: 0 0 15px 0;
        font-style: italic;
        color: rgba(0, 0, 0, 0.5);
    }
    .tables-manager p:last-child{
        margin: 0;
    }
    .tables-manager .item{
        position: relative;
        margin: 0 0 30px 0;
        padding: 15px;
        border-radius: 5px;
        box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        text-align: center;
    }
    .tables-manager .item h3{
        margin: 0 0 15px 0;
        font-size: 20px;
        font-weight: 400;
    }
    .tables-manager .item .infos{
        margin: 0 0 15px 0;
        font-style: italic;
    }
    .tables-manager .bg-grey{
        background: #DDD;
    }
    .tables-manager .help-block{
        margin-bottom: 0;
        font-style: italic;
        font-size: 12px;
    }

    .tables-manager .fields{
        margin: 15px 0 0 0;
    }
    .tables-manager .field{
        margin: 0 0 5px 0;
    }
    .fields-dialog .modal-dialog{
        width: 1000px;
        max-width: 95%;
    }
    .fields-head{
        margin: 0 0 10px 0;
        padding: 0 0 5px 0;
        font-weight: bold;
        border-bottom: 1px solid rgba(0, 0, 0, 0.3);
    }
</style>

<template>

    <div class="tables-manager">

        <div class="left">
            
            <h1>Types d'éléments du site</h1>
            <div class="hint">Nous définissons ici les types d'éléments présents sur le site. Pour chaque type, une table est créée dans la base de données. </div>

            <button type="button" class="btn btn-primary btn-block" @click="openCreateDialog()">
                <i class="fa fa-plus"></i> Créer un nouveau type d'élément
            </button>

        </div>

        <div class="right">
            <div class="row">
                <div class="col-sm-6 col-md-4" v-for="item in items">

                    <div class="item bg-grey">

                        <h3>{{ item.name }}</h3>
                        <div class="infos">
                            <div>
                                <span class="lab">Table : </span>
                                <span class="text-info">{{ item.table }}</span>
                            </div>
                            <div>
                                <span class="lab">Champs : </span>
                                <span class="text-info">{{ item.fields.length }}</span>
                            </div>
                        </div>

                        <button type="button"
                            class="btn btn-success btn-block"
                            @click="openUpdateDialog(item)">
                            <i class="fa fa-edit"></i> Modifier
                        </button>

                        <button type="button"
                            class="btn btn-primary btn-block"
                            @click="openFieldsDialog(item)">
                            <i class="fa fa-list"></i> Champs
                        </button>

                        <button type="button"
                            class="btn btn-danger btn-block"
                            @click="openDeleteDialog(item)">
                            <i class="fa fa-trash"></i> Supprimer
                        </button>

                    </div>

                </div>
            </div>
        </div>

        <!-- Create Dialog -->
        <div class="modal fade create-dialog"tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Nouvel élément</h4>
                    </div>
                    
                    <div class="modal-body">
                        
                        <form class="form-horizontal">

                            <div class="form-group">
                                <label :for="'name-' + id" class="col-sm-4 control-label">Nom :</label>
                                <div class="col-sm-8">
                                    <input type="text"
                                        required="required"
                                        class="form-control"
                                        :id="'name-' + id"
                                        v-model="fields.name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label :for="'alias-' + id" class="col-sm-4 control-label">Identifiant :</label>
                                <div class="col-sm-8">
                                    <input type="text"
                                        required="required"
                                        class="form-control"
                                        :id="'alias-' + id"
                                        v-model="fields.alias">
                                    <p class="help-block">Identifiant utilisé pour retrouver l'élément</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label :for="'table-' + id" class="col-sm-4 control-label">Nom de la table :</label>
                                <div class="col-sm-8">
                                    <input type="text"
                                        required="required"
                                        class="form-control"
                                        :id="'table-' + id"
                                        v-model="fields.table">
                                    <p class="help-block">Table utilisée dans la base de données pour ce type d'élément</p>
                                </div>
                            </div>

                        </form>

                        <div v-if="create_error" class="alert alert-danger">
                            {{ create_error.message }}
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" v-if="create_state == 'none'">
                            Annuler
                        </button>
                        <button type="button" 
                            class="btn btn-primary" 
                            @click="create()"
                            :disabled="create_state == 'pending'">

                            <span v-if="create_state == 'none'">
                                Initialiser l'élément
                            </span>
                            <span v-if="create_state == 'pending'">
                                <i class="fa fa-spinner fa-spin"></i> Initialisation en cours...
                            </span>

                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Update Dialog -->
        <div class="modal fade update-dialog"tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Modifier l'élément</h4>
                    </div>
                    
                    <div class="modal-body">
                        
                        <form class="form-horizontal">

                            <div class="form-group">
                                <label :for="'name-' + id" class="col-sm-4 control-label">Nom :</label>
                                <div class="col-sm-8">
                                    <input type="text"
                                        required="required"
                                        class="form-control"
                                        :id="'name-' + id"
                                        v-model="fields.name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label :for="'alias-' + id" class="col-sm-4 control-label">Identifiant :</label>
                                <div class="col-sm-8">
                                    <input type="text"
                                        required="required"
                                        class="form-control"
                                        :id="'alias-' + id"
                                        v-model="fields.alias">
                                    <p class="help-block">Identifiant utilisé pour retrouver l'élément</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label :for="'table-' + id" class="col-sm-4 control-label">Nom de la table :</label>
                                <div class="col-sm-8">
                                    <input type="text"
                                        required="required"
                                        class="form-control"
                                        :id="'table-' + id"
                                        v-model="fields.table">
                                    <p class="help-block">Table utilisée dans la base de données pour ce type d'élément</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label :for="'in_admin-' + id" class="col-sm-4 control-label">Administrable :</label>
                                <div class="col-sm-8">
                                    <div class="checkbox">
                                        <label :for="'in_admin-' + id">
                                            <input type="checkbox"
                                                :id="'in_admin-' + id"
                                                v-model="fields.in_admin">
                                        </label>
                                    </div>
                                </div>
                            </div>

                        </form>

                        <div v-if="update_error" class="alert alert-danger">
                            {{ update_error.message }}
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" v-if="update_state == 'none'">
                            Annuler
                        </button>
                        <button type="button" 
                            class="btn btn-primary" 
                            @click="update()"
                            :disabled="update_state == 'pending'">

                            <span v-if="update_state == 'none'">
                                Modifier l'élément
                            </span>
                            <span v-if="update_state == 'pending'">
                                <i class="fa fa-spinner fa-spin"></i> Modification en cours...
                            </span>

                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Delete Dialog -->
        <div class="modal fade delete-dialog"tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Supprimer : {{ this.fields.name }}</h4>
                    </div>
                    
                    <div class="modal-body">
                        Tous les éléments de ce type seront détruits et la table "{{ fields.table }}" sera supprimée de la base de données. Êtes-vous certain(e) de vouloir continuer ?

                        <div v-if="delete_error" class="alert alert-danger">
                            {{ delete_error }}
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" v-if="delete_state == 'none'">
                            <i class="fa fa-close"></i> Annuler
                        </button>
                        <button type="button" 
                            class="btn btn-danger" 
                            @click="deleteElement()"
                            :disabled="delete_state == 'pending'">

                            <span v-if="delete_state == 'none'">
                                Oui, supprimer !
                            </span>
                            <span v-if="delete_state == 'pending'">
                                <i class="fa fa-spinner fa-spin"></i> Suppression en cours...
                            </span>

                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Fields Dialog -->
        <div class="modal fade fields-dialog"tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Champs de : {{ this.fields.name }}</h4>
                    </div>
                    
                    <div class="modal-body">

                        <div class="text-right">
                            <button type="button" class="btn btn-default"
                                @click="addField()">
                                <i class="fa fa-plus"></i> Ajouter un champ
                            </button>
                        </div>
                        
                        <div class="fields">
                            <div class="fields-head">
                                <div class="row narrow">
                                    <div class="col col-sm-2">Type</div>
                                    <div class="col col-sm-3">Nom</div>
                                    <div class="col col-sm-3">Titre</div>
                                    <div class="col col-sm-3">Valeur par défaut</div>
                                    <div class="col col-sm-1"></div>
                                </div>
                            </div>

                            <div class="field" v-for="(field, idx) in fields.fields">
                                <div class="row narrow">
                                    <div class="col col-sm-2">
                                        <select class="form-control" v-model="field.type" @change="resetDefault(field)">
                                            <option value="text">Champ de texte</option>
                                            <option value="textarea">Champ de texte (multi-lignes)</option>
                                            <option value="select">Liste déroulante</option>
                                            <option value="editor">Éditeur de texte</option>
                                            <option value="checkbox">Case à cocher</option>
                                            <option value="media">Fichier média</option>
                                            <option value="medias">Fichiers média</option>
                                            <option value="url">URL</option>
                                            <option value="email">Email</option>
                                            <option value="date">Date</option>
                                        </select>
                                    </div>
                                    <div class="col col-sm-3">
                                        <input type="text" 
                                            v-model="field.name" 
                                            class="form-control" 
                                            placeholder="Nom du champ...">
                                    </div>
                                    <div class="col col-sm-3">
                                        <input type="text" v-model="field.title" class="form-control" placeholder="Titre...">
                                    </div>
                                    <div class="col col-sm-3">

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

                                    </div>
                                    <div class="col col-sm-1">
                                        <button type="button" class="btn btn-danger btn-block"
                                            @click="deleteField(idx)">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="fields_error" class="alert alert-danger">
                            {{ fields_error.message }}
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            <i class="fa fa-close"></i> Annuler
                        </button>
                        <button type="button" 
                            class="btn btn-primary" 
                            @click="updateFields()"
                            :disabled="fields_state == 'pending'">

                            <span v-if="fields_state == 'none'">
                                Enregistrer
                            </span>
                            <span v-if="fields_state == 'pending'">
                                <i class="fa fa-spinner fa-spin"></i> Enregistrement en cours...
                            </span>

                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data(){
            return{
                id: 0,
                items: [],

                create_state: 'none',
                create_error: null,

                update_state: 'none',
                update_error: null,

                delete_state: 'none',
                delete_error: null,

                fields_state: 'none',
                fields_error: null,

                fields: {
                    id: null,
                    name: '',
                    alias: '',
                    table: '',
                    in_admin: false,
                    fields: []
                }
            };
        },

        props: {
            'queries-base-url': {
                type: String,
                required: true,
                default: ''
            }
        },

        mounted() {
            var self = this;
            this.id = Math.floor(Math.random()*(9999-1000+1)+1000);
            this.refresh();
        },

        methods: {
            refresh: function()
            {
                return axios.get(this.queriesBaseUrl + 'tables').then(response => {
                    console.log(response.data);
                    this.items = response.data;
                }).catch(response => {

                });
            },

            openCreateDialog: function ()
            {
                this.fields.name = '';
                this.fields.alias = '';
                this.fields.table = '';

                $(this.$el).find('.create-dialog').modal('show');
            },

            closeCreateDialog: function ()
            {
                $(this.$el).find('.create-dialog').modal('hide');
            },

            create: function()
            {
                this.create_state = 'pending';
                this.create_error = null;

                axios.post(this.queriesBaseUrl + 'tables', this.fields).then(response => {
                    console.log('create success', response);
                    this.create_state = 'done';
                    this.items.push(response.data);
                    this.closeCreateDialog();
                }).catch(response => {
                    console.log('create error', response);
                    this.create_state = 'none';
                    this.create_error = response.response.data;
                });
            },

            copyFields: function(item){
                this.fields.id = item.id;
                this.fields.name = item.name;
                this.fields.alias = item.alias;
                this.fields.table = item.table;
                this.fields.in_admin = item.in_admin;
                this.fields.fields = item.fields;
            },

            openUpdateDialog: function (item)
            {
                this.copyFields(item);
                $(this.$el).find('.update-dialog').modal('show');
            },

            closeUpdateDialog: function ()
            {
                $(this.$el).find('.update-dialog').modal('hide');
            },

            update: function()
            {
                this.update_state = 'pending';
                this.update_error = null;

                axios.put(this.queriesBaseUrl + 'tables/' + this.fields.id, this.fields).then(response => {
                    this.update_state = 'none';
                    this.refresh();
                    this.closeUpdateDialog();
                }).catch(response => {
                    this.update_state = 'none';
                    this.update_error = response.response.data;
                });
            },

            openDeleteDialog: function(item)
            {
                this.copyFields(item);
                $(this.$el).find('.delete-dialog').modal('show');
            },

            closeDeleteDialog: function()
            {
                $(this.$el).find('.delete-dialog').modal('hide');
            },

            deleteElement: function()
            {
                this.delete_state = 'pending';
                this.delete_error = null;

                axios.delete(this.queriesBaseUrl + 'tables/' + this.fields.id).then(response => {
                    this.delete_state = 'none';
                    this.refresh();
                    this.closeDeleteDialog();
                }).catch(response => {
                    this.delete_state = 'none';
                    this.delete_error = response.response.data;
                });
            },

            openFieldsDialog: function(item)
            {
                this.copyFields(item);
                $(this.$el).find('.fields-dialog').modal('show');
            },

            closeFieldsDialog: function()
            {
                $(this.$el).find('.fields-dialog').modal('hide');
            },

            addField: function(){
                this.fields.fields.push({
                    type: 'text'
                });
            },

            deleteField: function(idx){
                this.fields.fields.splice(idx, 1);
            },

            resetDefault: function(field){
                console.log(field);
                field.default = null;
            },

            updateFields: function()
            {
                this.fields_state = 'pending';
                this.fields_error = null;

                axios.put(this.queriesBaseUrl + 'tables/' + this.fields.id, this.fields).then(response => {
                    this.fields_state = 'none';
                    this.refresh();
                    this.closeFieldsDialog();
                }).catch(response => {
                    this.fields_state = 'none';
                    this.fields_error = response.response.data;
                });
            }
        }
    }
</script>
