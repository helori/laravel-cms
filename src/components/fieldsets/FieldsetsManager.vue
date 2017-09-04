<style scoped>
h1{
    margin: 0 0 5px 0;
    font-size: 30px;
    font-weight: 300;
}
.subtitle{
    font-size: 16px;
    font-weight: 300;
    font-style: italic;
    margin: 0 0 15px 0;   
}
</style>

<template>

    <div>

        <div class="row">
            <div class="col-md-3 col-left">
                
                <div v-if="updateFieldsItem === null">

                    <h1>Fieldsets</h1>

                    <div class="subtitle">
                        <span v-if="readStatus === 'loading'">
                            Loading...
                        </span>
                        <span v-else-if="readStatus === 'success' && items.length === 0">
                            No result
                        </span>
                        <span v-else-if="readStatus === 'success'">
                            Fieldsets loaded !
                        </span>
                        <span v-else-if="readStatus === 'error'">
                            Something wrong happened...
                        </span>
                    </div>

                    <div class="alert alert-danger" v-if="readErrors">
                        {{ readErrors }}
                    </div>

                    <button type="button" class="btn btn-primary btn-block" @click="openCreate">
                        <i class="fa fa-plus"></i> New fieldset...
                    </button>

                </div>

                <div v-else>

                    <h1>Fieldset fields</h1>

                    <div class="subtitle">
                        {{ updateFieldsItem.title }}
                    </div>

                    <button type="button" class="btn btn-default btn-block" @click="openFields(null)">
                        <i class="fa fa-arrow-left"></i> Back to fieldsets
                    </button>

                </div>

            </div>

            <div class="col-md-9">

                <panel v-if="updateFieldsItem === null">
                    <div slot="body">

                        <div class="table-responsive">
                            <table class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>Fieldset name</th>
                                        <th>Database table</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, idx) in items">
                                        <td>{{ item.title }}</td>
                                        <td>{{ item.table }}</td>
                                        <td class="text-right">

                                            <div class="btn-group">

                                                <button type="button" @click="openUpdate(item)" class="btn btn-default">
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <button type="button" @click="openFields(item)" class="btn btn-default">
                                                    <i class="fa fa-list"></i>
                                                </button>

                                                <button type="button" @click="openDestroy(item)" class="btn btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                
                                            </div>
                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </panel>

                <panel v-else>
                    <div slot="body">

                        <field-manager
                            ref="fieldManager"
                            :uri-prefix="uriPrefix"
                            :fieldset-id="updateFieldsItem.id">
                        </field-manager>

                    </div>
                </panel>

            </div>
        </div>

        <!-- Pages dialogs -->

        <dialog-edit
            ref="createDialog"
            title="Create a new fieldset"
            :status="createStatus"
            @save="create">

            <div slot="body">
                <fieldset-form
                    v-if="createItem"
                    ref="createForm"
                    :item-org="createItem"
                    :errors-org="createErrors"
                    @change="createFormUpdated">
                </fieldset-form>
            </div>

        </dialog-edit>

        <dialog-edit
            ref="updateDialog"
            title="Update fieldset"
            :status="updateStatus"
            @save="update">

            <div slot="body">
                <fieldset-form
                    v-if="updateItem"
                    ref="updateForm"
                    :item-org="updateItem"
                    :errors-org="updateErrors"
                    @change="updateFormUpdated">
                </fieldset-form>
            </div>

        </dialog-edit>

        <dialog-destroy
            ref="destroyDialog"
            title="Delete fieldset"
            :message="destroyMessage"
            :status="destroyStatus"
            @destroy="destroy">
        </dialog-destroy>

    </div>
</template>

<script>

    import panel from 'vue-crud/src/widgets/Panel.vue'
    import dialogEdit from 'vue-crud/src/widgets/DialogEdit.vue'
    import dialogDestroy from 'vue-crud/src/widgets/DialogDestroy.vue'

    import fieldsetForm from './FieldsetForm.vue'
    import fieldManager from './FieldManager.vue'

    export default {

        components:{
            panel: panel,
            dialogEdit: dialogEdit,
            dialogDestroy: dialogDestroy,

            fieldsetForm: fieldsetForm,
            fieldManager: fieldManager
        },

        data(){
            return{
                items: [],
                
                // ---
                
                readStatus: null,
                readErrors: null,

                createItem: null,
                createErrors: null,
                createStatus: null,

                updateItem: null,
                updateErrors: null,
                updateStatus: null,

                destroyItem: {},
                destroyErrors: null,
                destroyStatus: null,

                updateFieldsItem: null
            };
        },

        props: {
            uriPrefix: {
                type: String,
                required: false,
                default: ''
            }
        },

        mounted() {
            this.read();
            this.resetCreateItem();
        },

        computed: {
            destroyMessage(){
                return 'You are about to delete the fieldset <strong>"' + this.destroyItem.title + '"</strong>.</p><p>This cannot be undone, so are you sure ?</p>';
            }
        },

        methods: {

            read() {
                
                this.readStatus = 'loading';

                var url = this.uriPrefix + '/api/fieldset';

                axios.get(url).then(response => {
                    
                    this.readStatus = 'success';
                    this.items = response.data;

                }).catch(response => {
                    
                    this.readStatus = 'error';
                    this.readErrors = response.response.data;

                });
            },

            resetCreateItem(){
                this.createItem = {
                    title: null
                };
            },

            openCreate(){
                this.createStatus = null;
                this.resetCreateItem();
                this.$refs.createDialog.open();
            },

            create(){

                var item = this.$refs.createForm.item;
                this.createStatus = 'loading';
                
                axios.post(this.uriPrefix +'/api/fieldset', item).then(response => {

                    this.createStatus = 'success';
                    this.$refs.createDialog.close();
                    this.read();

                }).catch(response => {
                    
                    this.createStatus = 'error';
                    this.createErrors = response.response.data;

                });
            },

            createFormUpdated(){
                this.createItem = this.$refs.createForm.item;
                this.createErrors = this.$refs.createForm.errors;
            },

            openUpdate(item){
                this.updateItem = item;
                this.updateErrors = null;
                this.updateStatus = null;
                this.$refs.updateDialog.open();
            },

            update(){

                var item = this.$refs.updateForm.item;
                this.updateStatus = 'loading';
                
                axios.put(this.uriPrefix +'/api/fieldset/' + item.id, item).then(response => {

                    this.updateStatus = 'success';
                    this.$refs.updateDialog.close();
                    this.read();

                }).catch(response => {
                    
                    this.updateStatus = 'error';
                    this.updateErrors = response.data;

                });
            },

            updateFormUpdated(){
                this.updateItem = this.$refs.updateForm.item;
                this.updateErrors = this.$refs.updateForm.errors;
            },

            openDestroy(item){
                
                this.destroyItem = item;
                this.destroyErrors = null;
                this.destroyStatus = null;
                this.$refs.destroyDialog.open();
            },

            destroy() {

                this.destroyStatus = 'loading';
                
                axios.delete(this.uriPrefix + '/api/fieldset/' + this.destroyItem.id).then(response => {

                    this.destroyStatus = 'success';
                    this.$refs.destroyDialog.close();
                    this.read();

                }).catch(response => {

                    this.destroyStatus = 'error';
                    this.destroyErrors = response.data;

                });

            },

            openFields(item){
                this.updateFieldsItem = item;
            },
        }
    }
</script>
