<style scoped>

</style>

<template>

    <div v-if="table">
        <crud-table
            :headers="headers"
            :attributes="attributes"
            :list-title="table.name"
            create-title="Ajouter"
            update-title="Modifier"
            delete-title="Supprimer"
            delete-text="Attention, cette opération est irréversible"
            no-results="Aucun résultat"
            :can-create="true"
            base-uri="/admin/api"
            :uri="'/element/' + table.id"
            create-form-component="form-element-create"
            update-form-component="form-element-update"
            table-row-component="row-element"
            :options="{
                table: table
            }">

        </crud-table>
    </div>

</template>

<script>
    export default {
        data(){
            return{
                table: null,
                headers: [],
                attributes: []
            };
        },

        props: {
            'table-id': {
                required: true
            },
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
            axios.get('/admin/api/table/' + this.tableId).then(response => {
                this.table = response.data;
                for(var i=0; i<this.table.fields.length; ++i){
                    if(this.table.fields[i].list){
                        this.headers.push(this.table.fields[i].title);
                    }
                    if(this.table.fields[i].create){
                        this.attributes.push({
                            name: this.table.fields[i].name,
                            default: this.table.fields[i].default
                        });
                    }
                }
            }).catch(response => {

            });
        }
    }
</script>
