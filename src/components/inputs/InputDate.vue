<style scoped>
    
</style>

<template>
    
    <input type="date"
        :id="name"
        class="form-control"
        v-model="dataValue"
        @change="updateValue" />

</template>

<script>
    export default {
        data(){
            return{
                loaded: false,
                dataValue: ''
            };
        },

        props: {
            // "value" is required to use v-model on the component
            'value': { 
              type: String,
              default: ''
            },
            'name': {
              type: String,
              default: ''
            }
        },

        watch: {
            value: function(val){
                if(!this.loaded){
                    this.dataValue = this.formatDate(new Date(val));
                    this.loaded = true;
                }
            }
        },

        mounted() {
            this.dataValue = this.formatDate(new Date(this.value));
        },

        methods: {
            updateValue: function () {
                // required to use v-model on the component :
                this.$emit('input', this.dataValue);
            },
            formatDate: function(date){
                var mm = date.getMonth() + 1; // getMonth() is zero-based
                var dd = date.getDate();
                return [
                    date.getFullYear(),
                    (mm>9 ? '' : '0') + mm,
                    (dd>9 ? '' : '0') + dd
                 ].join('-');
            }
        }
    }
</script>
