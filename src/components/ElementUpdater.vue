<style scoped>

</style>

<template>

    <div v-if="ready">
        <crud-form
            :id="id"
            base-uri="/admin/api"
            :uri="'/element/' + table.id"
            form-component="form-element-update"
            :options="options">
        </crud-form>
    </div>

</template>

<script>
    export default {
        data(){
            return{
                table: null,
                options: {},
                ready: false,
                needsReady: 0,
            };
        },

        props: {
            id: {
                required: true
            },
            tableId: {
                required: true
            },
            editorCss: {
                type: String,
                required: false,
                default: ''
            }
        },

        mounted() {
            axios.get('/admin/api/table/' + this.tableId).then(response => {
                this.table = response.data;
                this.options.table = this.table;
                var self = this;

                for(var i=0; i<this.options.table.fields.length; ++i){
                    var field = this.options.table.fields[i];

                    if(field.type == 'relation'){
                        ++this.needsReady;
                        this.loadOptions(field);
                    }
                }
                if(this.needsReady == 0){
                    this.ready = true;
                }

            }).catch(response => {

            });
        },

        methods: {
            loadOptions(field){
                axios.get('/admin/api/element/' + field.options.table).then(response => {
                    var opts = [];
                    for(var i=0; i<response.data.length; ++i){
                        opts.push({
                            value: response.data[i].id,
                            label: response.data[i][field.options.field]
                        });
                    }
                    this.options[field.name] = opts;
                    
                    --this.needsReady;
                    if(this.needsReady == 0){
                        this.ready = true;
                    }
                }).catch(response => {

                });
            }
        }
    }
</script>
