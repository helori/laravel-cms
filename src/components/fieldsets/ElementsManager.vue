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

        <div class="row" v-if="fieldset">

            <div class="col-md-3 col-left">

                <div v-if="updateItem === null">
                
                    <h1>{{ fieldset.title }}</h1>

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
                        <i class="fa fa-plus"></i> New element...
                    </button>

                </div>

                <div v-else>

                    <h1>Edit</h1>

                    <div class="subtitle">

                    </div>

                    <button type="button" class="btn btn-default btn-block" @click="openUpdate(null)">
                        <i class="fa fa-arrow-left"></i> Back to list
                    </button>

                    <button type="button" 
                        class="btn btn-primary btn-block"
                        @click="update"
                        :disabled="updateStatus === 'loading'">
                        <i class="fa fa-spinner fa-spin" v-if="updateStatus === 'loading'"></i> Save modifications
                    </button>

                    <div class="alert alert-danger" v-if="updateErrors">
                        {{ updateErrors }}
                    </div>

                    <div class="alert alert-success text-center" v-if="updateStatus === 'success'">
                        <i class="fa fa-smile-o"></i> Modifications saved !
                    </div>

                </div>

            </div>

            <div class="col-md-9">

                <panel v-if="updateItem === null">
                    <div slot="body">

                        <div class="table-responsive">
                            <table class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th v-for="field in fieldset.fields" v-if="field.show_in_list">{{ field.title }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, idx) in items">
                                        
                                        <td v-for="field in fieldset.fields" v-if="field.show_in_list">
                                            <div v-if="field.type == 'media'" style="width:120px">
                                                <media-thumb
                                                    :media="item[field.name][0]">
                                                </media-thumb>
                                            </div>
                                            <div v-else>{{ item[field.name] }}</div>
                                        </td>

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

                    </div>
                </panel>

                <panel v-else>
                    <div slot="body">

                        <element-form-update
                            ref="updateForm"
                            :fieldset="fieldset"
                            :uri-prefix="uriPrefix"
                            :item-org="updateItem"
                            :errors-org="updateErrors"
                            :editor-css="editorCss">
                        </element-form-update>

                    </div>
                </panel>

            </div>
        </div>

        <!-- dialogs -->

        <dialog-edit
            ref="createDialog"
            title="Create a new element"
            :status="createStatus"
            :errors="createErrors"
            @save="create">

            <div slot="body">
                <element-form-create
                    ref="createForm"
                    :fieldset="fieldset"
                    :uri-prefix="uriPrefix"
                    :item-org="createItem"
                    :errors-org="createErrors"
                    :editor-css="editorCss">
                </element-form-create>
            </div>

        </dialog-edit>

        <dialog-destroy
            ref="destroyDialog"
            title="Delete element"
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

    import elementFormCreate from './ElementFormCreate.vue'
    import elementFormUpdate from './ElementFormUpdate.vue'
    import mediaThumb from '../medias/MediaThumb.vue'

    export default {

        components:{
            panel: panel,
            dialogEdit: dialogEdit,
            dialogDestroy: dialogDestroy,

            elementFormCreate: elementFormCreate,
            elementFormUpdate: elementFormUpdate,

            mediaThumb: mediaThumb
        },

        data(){
            return{
                items: [],
                fieldset: {},
                
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
                required: true
            },
            editorCss: {
                type: String,
                required: false,
                default: ''
            }
        },

        mounted() {
            axios.get(this.uriPrefix + '/api/fieldset/' + this.fieldsetId).then(r => {
                this.fieldset = r.data;
                this.resetCreateItem();
                this.read();
            });
        },

        computed: {
            destroyMessage(){
                return 'You are about to delete the element.</p><p>This cannot be undone, so are you sure ?</p>';
            }
        },

        methods: {

            read() {
                
                this.readStatus = 'loading';

                var url = this.uriPrefix + '/api/fieldset/' + this.fieldsetId + '/element';

                axios.get(url).then(response => {
                    
                    this.readStatus = 'success';
                    this.items = response.data;

                }).catch(response => {
                    
                    this.readStatus = 'error';
                    this.readErrors = response.response.data;

                });
            },

            openCreate(){
                this.resetCreateItem();
                this.createErrors = null;
                this.createStatus = null;
                this.$refs.createDialog.open();
            },

            resetCreateItem(){
                var createItem = {};
                _.forEach(this.fieldset.fields, function(field){
                    if(field.use_when_create){
                        if(field.type == 'media'){
                            createItem[field.name] = [];
                        }else{
                            createItem[field.name] = field.default;
                        }
                    }
                });
                this.createItem = createItem;
            },

            create(){

                var item = this.$refs.createForm.item;
                this.createStatus = 'loading';
                
                axios.post(this.uriPrefix + '/api/fieldset/' + this.fieldsetId + '/element', item).then(response => {

                    this.createStatus = 'success';
                    this.$refs.createDialog.close();
                    this.read();

                }).catch(response => {
                    
                    this.createStatus = 'error';
                    this.createErrors = response.response.data;

                });
            },

            openUpdate(item){
                this.updateItem = item;
                this.updateErrors = null;
                this.updateStatus = null;
            },

            update(){

                var item = this.$refs.updateForm.item;
                this.updateStatus = 'loading';
                
                axios.put(this.uriPrefix + '/api/fieldset/' + this.fieldsetId + '/element/' + item.id, item).then(response => {

                    this.updateStatus = 'success';
                    this.read();

                }).catch(response => {
                    
                    this.updateStatus = 'error';
                    this.updateErrors = response.data;

                });
            },

            openDestroy(item){
                
                this.destroyItem = item;
                this.destroyErrors = null;
                this.destroyStatus = null;
                this.$refs.destroyDialog.open();
            },

            destroy() {

                this.destroyStatus = 'loading';
                
                axios.delete(this.uriPrefix + '/api/fieldset/' + this.fieldsetId + '/element/' + this.destroyItem.id).then(response => {

                    this.destroyStatus = 'success';
                    this.$refs.destroyDialog.close();
                    this.read();

                }).catch(response => {

                    this.destroyStatus = 'error';
                    this.destroyErrors = response.data;

                });

            }
        }
    }
</script>
