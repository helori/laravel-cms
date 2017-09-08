<style scoped>
h1{
    margin: 0 0 30px 0;
    font-size: 30px;
    font-weight: 300;
    text-align: center;
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

        <h1>{{ fieldset.title }}</h1>

        <div class="row" v-if="fieldset">

            <div class="col-md-10 col-md-offset-1">

                <panel>
                    <div slot="body">

                        <element-form-update
                            ref="updateForm"
                            :fieldset="fieldset"
                            :uri-prefix="uriPrefix"
                            :item-org="item"
                            :errors-org="updateErrors"
                            :editor-css="editorCss"
                            :editor-assets-url="editorAssetsUrl"
                            :editor-options="editorOptions"
                            @change="updateFormUpdated">
                        </element-form-update>

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
                </panel>

            </div>
        </div>

    </div>
</template>

<script>

    import panel from 'vue-crud/src/widgets/Panel.vue'
    import elementFormUpdate from './ElementFormUpdate.vue'

    export default {

        components:{
            panel: panel,
            elementFormUpdate: elementFormUpdate,
        },

        data(){
            return{
                item: {},
                fieldset: {},

                modifying: false,
                
                readStatus: null,
                readErrors: null,

                updateErrors: null,
                updateStatus: null,
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
            },
            editorAssetsUrl: {
                type: String,
                required: false,
                default: ''
            },
            editorOptions: {
                type: Object,
                required: false,
                default(){
                    return {};
                }
            }
        },

        mounted() {
            axios.get(this.uriPrefix + '/api/fieldset/' + this.fieldsetId).then(r => {
                this.fieldset = r.data;
                this.read();
            });
        },

        methods: {

            read() {
                
                this.readStatus = 'loading';

                var url = this.uriPrefix + '/api/fieldset/' + this.fieldsetId + '/element';

                axios.get(url).then(response => {
                    
                    this.readStatus = 'success';
                    this.item = response.data;

                }).catch(response => {
                    
                    this.readStatus = 'error';
                    this.readErrors = response.response.data;

                });
            },

            update(){

                var item = this.$refs.updateForm.item;
                this.updateStatus = 'loading';
                
                axios.put(this.uriPrefix + '/api/fieldset/' + this.fieldsetId + '/element/' + item.id, item).then(response => {

                    this.updateStatus = 'success';
                    this.modifying = false;
                    this.read();

                }).catch(response => {
                    
                    this.updateStatus = 'error';
                    this.updateErrors = response.data;

                });
            },

            updateFormUpdated(){
                this.item = this.$refs.updateForm.item;
                this.updateErrors = this.$refs.updateForm.errors;
                this.modifying = true;
            },
        }
    }
</script>
