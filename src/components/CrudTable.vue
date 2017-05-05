<style scoped>
    
</style>

<template>
    <div>

        <div class="panel no-padding">

            <div class="panel-heading" v-if="listTitle">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h1>{{ listTitle }}</H1>
                </div>
            </div>

            <div class="panel-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th v-for="header in headers">{{ header }}</th>
                            <th class="text-right">
                                <a class="btn btn-primary" @click="showCreateModal" v-if="canCreate">
                                    <i class="fa fa-plus"></i>
                                </a>
                            </th>
                        </tr>
                    </thead>
                    <tbody v-if="items.length > 0">
                        <tr v-for="item in items"
                            :is="tableRowComponent"
                            :item="item"
                            :options="options"
                            v-on:update="showUpdateModal(item)"
                            v-on:destroy="showDestroyModal(item)">
                        </tr>
                    </tbody>
                </table>
                <p class="no-results" v-if="items.length === 0">
                    {{ noResults }}
                </p>
            </div>
        </div>

        <!-- Create Dialog -->
        <div id="modal-create" class="modal fade"tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">
                            {{ createTitle }}
                        </h4>
                    </div>
                    
                    <div class="modal-body">

                        <form name="createForm" class="form-horizontal" role="form">
                            <component
                                :is="createFormComponent"
                                :form-element="createForm"
                                mode="create_"
                                :options="options"
                                v-on:submitted="store()">
                            </component>
                        </form>

                        <!-- Form Errors -->
                        <div v-if="hasErrors(createForm)">
                            <div class="alert alert-danger" v-for="(message, field) in createForm.errors">
                                {{ message }}
                            </div>
                        </div>
                        
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary" @click="store">
                            Valider
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Update Dialog -->
        <div id="modal-update" class="modal fade"tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">
                            {{ updateTitle }}
                        </h4>
                    </div>
                    
                    <div class="modal-body">

                        <form name="updateForm" class="form-horizontal" role="form">
                            <component
                                :is="updateFormComponent"
                                :form-element="updateForm"
                                :item-to-update="itemToUpdate"
                                mode="update_"
                                :options="options"
                                v-on:submitted="update()">
                            </component>
                        </form>

                        <!-- Form Errors -->
                        <div v-if="hasErrors(updateForm)">
                            <div class="alert alert-danger" v-for="(message, field) in updateForm.errors">
                                {{ message }}
                            </div>
                        </div>
                        
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary" @click="update">
                            Valider
                        </button>
                    </div>

                </div>
            </div>
        </div>


        <!-- Destroy Dialog -->
        <div id="modal-destroy" class="modal fade"tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">
                            {{ deleteTitle }}
                        </h4>
                    </div>
                    
                    <div class="modal-body">
                        <p>{{ deleteText }}</p>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-danger" @click="destroy()">
                            Supprimer
                        </button>
                    </div>

                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            var createForm = {
                errors: {}
            };
            var updateForm = {
                errors: {}
            };
            for(var i=0; i<this.attributes.length; ++i){
                createForm[this.attributes[i].name] = this.attributes[i].default;
                updateForm[this.attributes[i].name] = this.attributes[i].default;
            }
            return {
                items: [],
                createForm: createForm,
                updateForm: updateForm,
                itemToUpdate: null
            };
        },

        /*
         * The component's props.
         */
        props: [
            'headers',
            'attributes',
            'listTitle',
            'createTitle',
            'updateTitle',
            'deleteTitle',
            'deleteText',
            'noResults',
            'getUrl',
            'createUrl',
            'deleteUrl',
            'updateUrl',
            'createFormComponent',
            'updateFormComponent',
            'tableRowComponent',
            'options',
            'canCreate'
        ],

        /**
         * Prepare the component (Vue 1.x).
         */
        ready() {
            this.prepareComponent();
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component.
             */
            prepareComponent() {
                this.getItems();

                $('#modal-create').on('shown.bs.modal', () => {
                    var firstInput = $('#modal-create').find('input,select,checkbox').first();
                    if(firstInput.length > 0)
                        firstInput.focus();
                });
                $('#modal-update').on('shown.bs.modal', () => {
                    var firstInput = $('#modal-update').find('input,select,checkbox').first();
                    if(firstInput.length > 0)
                        firstInput.select().focus();
                });
            },

            /**
             * Get all of the items.
             */
            getItems() {
                this.$http.get(this.getUrl).then(response => {
                    this.items = response.data;
                });
            },

            /**
             * Show the form for creating new items.
             */
            showCreateModal() {
                $('#modal-create').modal('show');
            },

            /**
             * Create a new item.
             */
            store() {
                this.persistItem(
                    'post', this.createUrl,
                    this.createForm, '#modal-create'
                );
            },

            /**
             * Edit the given item.
             */
            showUpdateModal(item) {
                this.itemToUpdate = item;

                this.updateForm.id = item.id;
                for(var i=0; i<this.attributes.length; ++i){
                    this.updateForm[this.attributes[i].name] = item[this.attributes[i].name];
                    //console.log(this.attributes[i].name, item[this.attributes[i].name]);
                }
                this.updateForm.redirect = item.redirect;

                $('#modal-update').modal('show');
            },

            /**
             * Update the item being edited.
             */
            update() {
                this.persistItem(
                    'put', this.updateUrl + '/' + this.updateForm.id,
                    this.updateForm, '#modal-update'
                );
            },

            /**
             * Persist the item to storage using the given form.
             */
            persistItem(method, uri, form, modal) {
                form.errors = [];

                this.$http[method](uri, form)
                    .then(response => {
                        this.getItems();

                        form.name = '';
                        form.errors = {};

                        $(modal).modal('hide');
                    })
                    .catch(response => {
                        if (typeof response.data === 'object') {
                            form.errors = response.data;
                            console.log(form.errors);
                        } else {
                            form.errors = {message: "Une erreur s'est produite"};
                        }
                    });
            },

            /**
             * Show the form for destroying an item.
             */
            showDestroyModal(item) {
                this.itemToDestroy = item;
                $('#modal-destroy').modal('show');
            },

            /**
             * Destroy the given item.
             */
            destroy() {
                this.$http.delete(this.deleteUrl + '/' + this.itemToDestroy.id).then(response => {
                    this.getItems();
                    $('#modal-destroy').modal('hide');
                }).catch(response => {
                    if (typeof response.data === 'object') {
                        form.errors = response.data.message;
                    } else {
                        form.errors = {message: "Une erreur s'est produite"};
                    }
                });
            },

            hasErrors(form) {
                return ! _.isEmpty(form.errors);
            }
        }
    }
</script>
