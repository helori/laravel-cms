<style scoped>

</style>

<template>

    <div v-if="table">
        <crud-form
            :id="id"
            base-uri="/admin/api"
            :uri="'/element/' + table.id"
            form-component="form-element-update"
            :options="{
                table: table
            }">
        </crud-form>
    </div>

</template>

<script>
    export default {
        data(){
            return{
                table: null
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
            }).catch(response => {

            });
        }
    }
</script>
