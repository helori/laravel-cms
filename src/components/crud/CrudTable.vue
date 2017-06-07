<style scoped>
    #modal-update{
        max-width: 90%;
    }
</style>

<template>
    <div>

        <slot name="before-table"></slot>

        <panel>
            <div slot="title">
                {{ listTitle }}
            </div>
            <div slot="body">
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
        </panel>

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
                                ref="createFormComp"
                                :is="createFormComponent"
                                :item-org="createForm"
                                :errors-org="createErrors"
                                :base-uri="baseUri"
                                :uri="uri"
                                :options="options">
                            </component>
                        </form>

                        <!-- Form Errors -->
                        <!--div v-if="hasErrors(createErrors)">
                            <div class="alert alert-danger" v-for="(message, field) in createErrors">
                                {{ message }}
                            </div>
                        </div-->
                        
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary" @click="store()">
                            Valider
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <!-- Update Dialog -->
        <div id="modal-update" class="modal fade"tabindex="-1" role="dialog">
            <div class="modal-dialog" :style="'width: ' + updateDialogWidth + 'px'">
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
                                ref="updateFormComp"
                                :is="updateFormComponent"
                                :item-org="updateForm"
                                :errors-org="updateErrors"
                                :base-uri="baseUri"
                                :uri="uri"
                                :options="options">
                            </component>
                        </form>

                        <!-- Form Errors -->
                        <!--div v-if="hasErrors(updateErrors)">
                            <div class="alert alert-danger" v-for="(message, field) in updateErrors">
                                {{ message }}
                            </div>
                        </div-->
                        
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-primary" @click="update()">
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
            return {
                items: [],
                createForm: {},
                updateForm: {},
                createErrors: [],
                updateErrors: []
            };
        },

        /*
         * The component's props.
         */
        props: {
            headers: {
                type: Array,
                required: true,
                default: []
            },
            attributes: {
                type: Array,
                required: true,
                default: []
            },
            listTitle: {
                type: String,
                required: false,
                default: ''
            },
            createTitle: {
                type: String,
                required: false,
                default: ''
            },
            updateTitle: {
                type: String,
                required: false,
                default: ''
            },
            deleteTitle: {
                type: String,
                required: false,
                default: ''
            },
            deleteText: {
                type: String,
                required: false,
                default: ''
            },
            noResults: {
                type: String,
                required: false,
                default: ''
            },
            canCreate: {
                type: Boolean,
                required: false,
                default: true
            },
            baseUri: {
                type: String,
                required: true
            },
            uri: {
                type: String,
                required: true
            },
            createFormComponent: {
                required: true
            },
            updateFormComponent: {
                required: true
            },
            tableRowComponent: {
                required: true
            },
            options: {
                type: Object,
                required: false
            },
            updateDialogWidth: {
                type: Number,
                required: false,
                default: 600
            }
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.initFormValues();

            this.getItems();

            this.$on('needsRefresh', function(){
                this.getItems();
            });

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

        methods: {

            initFormValues(item) {
                for(var i=0; i<this.attributes.length; ++i){
                    this.createForm[this.attributes[i].name] = this.attributes[i].default;
                    this.updateForm[this.attributes[i].name] = item ? item[this.attributes[i].name] : this.attributes[i].default;
                }
            },

            getItems() {
                axios.get(this.baseUri + this.uri).then(response => {
                    this.items = response.data;
                });
            },

            showCreateModal() {
                this.initFormValues();
                $('#modal-create').modal('show');
            },

            store() {
                this.createErrors = {};
                var item = this.$refs.createFormComp.item;

                return axios.post(this.baseUri + this.uri, item).then(response => {
                    this.getItems();
                    $('#modal-create').modal('hide');
                })
                .catch(response => {
                    this.createErrors = response.body;
                });
            },

            showUpdateModal(item) {
                //this.updateForm.id = item.id;
                //this.initFormValues(item);
                this.updateForm = item;
                $('#modal-update').modal('show');
            },

            /**
             * Update the item being edited.
             */
            update() {
                this.updateErrors = {};
                var item = this.$refs.updateFormComp.item;
                
                return axios.put(this.baseUri + this.uri + '/' + item.id, item).then(response => {
                    this.getItems();
                    $('#modal-update').modal('hide');
                })
                .catch(response => {
                    this.updateErrors = response.body;
                });
            },

            showDestroyModal(item) {
                this.itemToDestroy = item;
                $('#modal-destroy').modal('show');
            },

            destroy() {
                axios.delete(this.baseUri + this.uri + '/' + this.itemToDestroy.id).then(response => {
                    this.getItems();
                    $('#modal-destroy').modal('hide');
                }).catch(response => {
                    if (typeof response.data === 'object') {
                        errors = response.data.message;
                    } else {
                        errors = {message: "Une erreur s'est produite"};
                    }
                });
            },

            hasErrors(errors) {
                return ! _.isEmpty(errors);
            }
        }
    }
</script>
