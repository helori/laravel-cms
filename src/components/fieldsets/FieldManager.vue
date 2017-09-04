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
            <div class="col-md-6">
                
                <div class="subtitle">
                    <span v-if="readStatus === 'loading'">
                        Loading...
                    </span>
                    <span v-else-if="readStatus === 'success' && items.length === 0">
                        No result
                    </span>
                    <span v-else-if="readStatus === 'success'">
                        Fields loaded !
                    </span>
                    <span v-else-if="readStatus === 'error'">
                        Something wrong happened...
                    </span>
                </div>

                <div class="alert alert-danger" v-if="readErrors">
                    {{ readErrors }}
                </div>

            </div>

            <div class="col-md-6">

                <button type="button" class="btn btn-primary btn-block" @click="openCreate">
                    <i class="fa fa-plus"></i> New field...
                </button>

            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-condensed table-striped">
                <thead>
                    <tr>
                        <th>Display name</th>
                        <th>Field type</th>
                        <th>Table field name</th>
                        <th>Default value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, idx) in items">
                        <td>{{ item.title }}</td>
                        <td>{{ item.type }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.default }}</td>
                        <td class="text-right">

                            <div class="btn-group">

                                <button type="button" @click="openUpdate(item)" class="btn btn-default">
                                    <i class="fa fa-edit"></i>
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

        <!-- Pages dialogs -->

        <dialog-edit
            ref="createDialog"
            title="Create a new field"
            :status="createStatus"
            @save="create">

            <div slot="body">
                <field-form
                    ref="createForm"
                    :item-org="createItem"
                    :errors-org="createErrors"
                    @change="createFormUpdated">
                </field-form>
            </div>

        </dialog-edit>

        <dialog-edit
            ref="updateDialog"
            title="Update field"
            :status="updateStatus"
            @save="update">

            <div slot="body">
                <field-form
                    ref="updateForm"
                    :item-org="updateItem"
                    :errors-org="updateErrors"
                    @change="updateFormUpdated">
                </field-form>
            </div>

        </dialog-edit>

        <dialog-destroy
            ref="destroyDialog"
            title="Delete field"
            :message="destroyMessage"
            :status="destroyStatus"
            :errors="destroyErrors"
            @destroy="destroy">
        </dialog-destroy>

    </div>
</template>

<script>

    import panel from 'vue-crud/src/widgets/Panel.vue'
    import dialogEdit from 'vue-crud/src/widgets/DialogEdit.vue'
    import dialogDestroy from 'vue-crud/src/widgets/DialogDestroy.vue'

    import fieldForm from './FieldForm.vue'

    export default {

        components:{
            panel: panel,
            dialogEdit: dialogEdit,
            dialogDestroy: dialogDestroy,

            fieldForm: fieldForm
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
                destroyStatus: null
            };
        },

        props: {
            uriPrefix: {
                type: String,
                required: false,
                default: ''
            },
            fieldsetId: {
                type: Number,
                required: true
            }
        },

        mounted() {
            this.read();
            this.resetCreateItem();
        },

        computed: {
            destroyMessage(){
                return 'You are about to delete the field <strong>"' + this.destroyItem.title + '"</strong>.</p><p>This cannot be undone, so are you sure ?</p>';
            }
        },

        methods: {

            read() {
                
                this.readStatus = 'loading';

                var url = this.uriPrefix + '/api/fieldset/' + this.fieldsetId + '/field';

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
                    fieldset_id: this.fieldsetId,
                    type: 'text',
                    title: null,
                    default: null,
                    use_when_create: true,
                    use_when_update: true,
                    show_in_list: true
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
                
                axios.post(this.uriPrefix + '/api/fieldset/' + this.fieldsetId + '/field', item).then(response => {

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
                
                axios.put(this.uriPrefix + '/api/fieldset/' + this.fieldsetId + '/field/' + item.id, item).then(response => {

                    this.updateStatus = 'success';
                    this.$refs.updateDialog.close();
                    this.read();

                }).catch(response => {
                    
                    this.updateStatus = 'error';
                    this.updateErrors = response.response.data;

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
                
                axios.delete(this.uriPrefix + '/api/fieldset/' + this.fieldsetId + '/field/' + this.destroyItem.id).then(response => {

                    this.destroyStatus = 'success';
                    this.$refs.destroyDialog.close();
                    this.read();

                }).catch(response => {

                    this.destroyStatus = 'error';
                    this.destroyErrors = response.response.data;

                });

            }
        }
    }
</script>
