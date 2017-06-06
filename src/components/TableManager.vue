<style scoped>
    .table-manager{
        position: absolute;
        top: 50px; bottom: 0;
        left: 0; right: 0;
        width: 100%;
    }
    .table-manager .left{
        z-index: 1;
        position: absolute;
        top: 0; bottom: 0; left: 0;
        width: 500px; height: 100%;
        padding: 30px;
        overflow-x: hidden;
        overflow-y: scroll;
        box-shadow: 1px 0 4px rgba(0, 0, 0, 0.5);
    }
    .table-manager .right{
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

    .table-manager h1{
        margin: 0 0 15px 0;
        font-size: 30px;
        font-weight: 400;
    }
    .table-manager h2{
        margin: 0 0 15px 0;
        font-size: 16px;
        font-weight: 500;
    }
    table{
        background: white;
    }
    table > thead > tr > th,
    table > tbody > tr > td{
        vertical-align: middle;
    }
    td.actions{
        width: 150px;
    }
    .table-manager .modal-dialog{
        width: 1000px;
        max-width: 90%;
    }
</style>

<template>

    <div class="table-manager" v-if="table">

        <div class="left">
            
            <h1>{{ table.name }}</h1>
            
            <button type="button" class="btn btn-primary btn-block" @click="openCreateDialog()">
                <i class="fa fa-plus"></i> Créer
            </button>

        </div>

        <div class="right">

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th v-for="field in table.fields" v-if="field.list">
                            {{ field.title }}
                        </th>
                        <th class="actions"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items">
                        <td v-for="field in table.fields" v-if="field.list">
                            <div v-if="field.type == 'checkbox'" class="text-center">
                                <i class="fa fa-check fa-2x text-success" v-if="item[field.name]"></i>
                                <i class="fa fa-close fa-2x text-danger" v-else></i>
                            </div>
                            <div v-else>
                                {{ item[field.name] }}
                            </div>
                        </td>
                        <td class="actions">
                            <button class="btn btn-success" @click="openUpdateDialog(item)">
                                <i class="fa fa-edit"></i> Éditer
                            </button>
                            <button class="btn btn-danger" @click="openDeleteDialog(item)">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>

        <!-- Create Dialog -->
        <div class="modal fade create-dialog"tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Créer...</h4>
                    </div>
                    
                    <div class="modal-body">
                        
                        <form class="form-horizontal">

                            <div class="form-group" v-for="field in table.fields" v-if="field.create">
                                <label class="control-label col-sm-4" :for="field.name + '-' + id">{{ field.title }} :</label>
                                <div class="col-sm-8">

                                    <input type="text" 
                                        v-if="field.type == 'text'"
                                        :id="field.name + '-' + id"
                                        :placeholder="field.placeholder"
                                        class="form-control"
                                        v-model="fields[field.name]">

                                    <textarea
                                        v-if="field.type == 'textarea'"
                                        :id="field.name + '-' + id"
                                        :placeholder="field.placeholder"
                                        class="form-control"
                                        v-model="fields[field.name]"></textarea>

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
                                Créer
                            </span>
                            <span v-if="create_state == 'pending'">
                                <i class="fa fa-spinner fa-spin"></i> Création en cours...
                            </span>

                        </button>
                    </div>

                </div>
            </div>
        </div>

        <slide-panel ref="slidePanel">
            <div slot="content">
                <form class="form-horizontal">

                    <div class="form-group" v-for="(field, idx) in table.fields">
                        <label class="control-label col-sm-4" :for="field.name + '-' + id">{{ field.title }} :</label>
                        <div class="col-sm-8">

                            <input type="text" 
                                v-if="field.type == 'text'"
                                :id="field.name + '-' + id"
                                :placeholder="field.placeholder"
                                class="form-control"
                                v-model="fields[field.name]">

                            <textarea
                                v-if="field.type == 'textarea'"
                                :id="field.name + '-' + id"
                                :placeholder="field.placeholder"
                                class="form-control"
                                v-model="fields[field.name]"></textarea>

                            <input-date
                                v-if="field.type == 'date'"
                                v-model="fields[field.name]"
                                :name="field.name + '-' + id">
                            </input-date>

                            <input-checkbox 
                                v-if="field.type == 'checkbox'"
                                v-model="fields[field.name]"
                                :name="field.name + '-' + id">
                            </input-checkbox>

                            <input-editor 
                                v-if="field.type == 'editor'"
                                v-model="fields[field.name]"
                                :css="editorCss"
                                :name="field.name + '-' + id">
                            </input-editor>

                            <input-media
                                v-if="field.type == 'media'"
                                v-model="fields[field.name]"
                                :name="field.name + '-' + id">
                            </input-media>

                            <input-medias
                                v-if="field.type == 'medias'"
                                v-model="fields[field.name]"
                                :name="field.name + '-' + id">
                            </input-medias>

                        </div>
                    </div>

                </form>

                <div v-if="update_error" class="alert alert-danger">
                    {{ update_error.message }}
                </div>

                <div class="row">
                    <div class="col-sm-8 col-sm-offset-4">
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="button" 
                                    class="btn btn-default btn-block" 
                                    @click="closeUpdateDialog()" 
                                    v-if="update_state == 'none'">
                                    Fermer
                                </button>
                            </div>
                            <div class="col-sm-8">
                                <button type="button" 
                                    class="btn btn-primary btn-block" 
                                    @click="update()"
                                    :disabled="update_state == 'pending'">

                                    <span v-if="update_state == 'none'">
                                        Enregistrer
                                    </span>
                                    <span v-if="update_state == 'pending'">
                                        <i class="fa fa-spinner fa-spin"></i> Enregistrement en cours...
                                    </span>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </slide-panel>

        <!-- Update Dialog -->
        <!--div class="modal fade update-dialog"tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Modifier</h4>
                    </div>
                    
                    <div class="modal-body">
                        
                        <form class="form-horizontal">

                            <div class="form-group" v-for="(field, idx) in table.fields">
                                <label class="control-label col-sm-4" :for="field.name + '-' + id">{{ field.title }} :</label>
                                <div class="col-sm-8">

                                    <input type="text" 
                                        v-if="field.type == 'text'"
                                        :id="field.name + '-' + id"
                                        :placeholder="field.placeholder"
                                        class="form-control"
                                        v-model="fields[field.name]">

                                    <textarea
                                        v-if="field.type == 'textarea'"
                                        :id="field.name + '-' + id"
                                        :placeholder="field.placeholder"
                                        class="form-control"
                                        v-model="fields[field.name]"></textarea>

                                    <input-checkbox 
                                        v-if="field.type == 'checkbox'"
                                        v-model="fields[field.name]"
                                        :name="field.name + '-' + id">
                                    </input-checkbox>

                                    <input-editor 
                                        v-if="field.type == 'editor'"
                                        v-model="fields[field.name]"
                                        :css="editorCss"
                                        :name="field.name + '-' + id">
                                    </input-editor>

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
                                Modifier
                            </span>
                            <span v-if="update_state == 'pending'">
                                <i class="fa fa-spinner fa-spin"></i> Modification en cours...
                            </span>

                        </button>
                    </div>

                </div>
            </div>
        </div-->

        <!-- Delete Dialog -->
        <div class="modal fade delete-dialog"tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Supprimer</h4>
                    </div>
                    
                    <div class="modal-body">
                        C'est sûr ?

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

    </div>
</template>

<script>
    export default {
        data(){
            return{
                id: 0,
                table: null,
                items: [],
                item_id: 0,

                create_state: 'none',
                create_error: null,

                update_state: 'none',
                update_error: null,

                delete_state: 'none',
                delete_error: null,

                fields: {}
            };
        },

        props: {
            'table-id': '',
            'queries-base-url': {
                type: String,
                required: true,
                default: ''
            },
            'editor-css': {
                type: String,
                required: false,
                default: ''
            }
        },

        mounted() {
            this.id = Math.floor(Math.random()*(9999-1000+1)+1000);
            this.refresh();
        },

        methods: {
            refresh: function()
            {
                return axios.get(this.queriesBaseUrl + 'tables/' + this.tableId).then(response => {
                    this.table = response.data;

                    return axios.get(this.queriesBaseUrl + 'table/' + this.tableId).then(response => {
                        this.items = response.data;
                    }).catch(response => {

                    });

                }).catch(response => {

                });
            },

            openCreateDialog: function ()
            {
                //this.copyFields();
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

                var fields = {};
                for(var i=0; i<this.fields.length; ++i){
                    fields[this.fields[i].name] = this.fields[i].value;
                }

                console.log(fields);

                axios.post(this.queriesBaseUrl + 'table/' + this.tableId, fields).then(response => {
                    console.log('create success', response);
                    this.create_state = 'none';
                    this.items.push(response.data);
                    this.closeCreateDialog();
                }).catch(response => {
                    console.log('create error', response);
                    this.create_state = 'none';
                    this.create_error = response.response.data;
                });
            },

            /*copyFields: function(item){
                this.fields = this.table.fields;

                if(item){
                    for(var i=0; i< item.fields.length; ++i){
                        var field = item.fields[i];
                        this.fields[field.name] = field.name;
                    }
                }
            },*/

            closePanel(){
                this.$refs.slidePanel.setOpen(false);
            },

            openUpdateDialog: function (item)
            {
                this.item_id = item.id;
                this.fields = item;
                //this.copyFields(item);

                this.$refs.slidePanel.setOpen(true);
                //$(this.$el).find('.update-dialog').modal('show');
            },

            closeUpdateDialog: function ()
            {
                this.$refs.slidePanel.setOpen(false);
                //$(this.$el).find('.update-dialog').modal('hide');
            },

            update: function()
            {
                this.update_state = 'pending';
                this.update_error = null;

                axios.put(this.queriesBaseUrl + 'table/' + this.tableId + '/' + this.item_id, this.fields).then(response => {
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
                this.item_id = item.id;
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

                axios.delete(this.queriesBaseUrl + 'table/' + this.tableId + '/' + this.item_id).then(response => {
                    this.delete_state = 'none';
                    this.refresh();
                    this.closeDeleteDialog();
                }).catch(response => {
                    this.delete_state = 'none';
                    this.delete_error = response.response.data;
                });
            }
        }
    }
</script>
