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
            <div class="row">
                <div class="col-sm-6 col-md-4" v-for="item in items">

                    <div class="item bg-grey">

                        <h3>{{ item.id }}</h3>
                        
                        <button type="button"
                            class="btn btn-success btn-block"
                            @click="openUpdateDialog(item)">
                            <i class="fa fa-edit"></i> Modifier
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
                        <h4 class="modal-title">Créer...</h4>
                    </div>
                    
                    <div class="modal-body">
                        
                        <form class="form-horizontal">

                            

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

        <!-- Update Dialog -->
        <div class="modal fade update-dialog"tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Modifier</h4>
                    </div>
                    
                    <div class="modal-body">
                        
                        <form class="form-horizontal">

                            

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
        </div>

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

                create_state: 'none',
                create_error: null,

                update_state: 'none',
                update_error: null,

                delete_state: 'none',
                delete_error: null
            };
        },

        props: {
            'table-id': '',
            'queries-base-url': {
                type: String,
                required: true,
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
