<style scoped>
    
</style>

<template>
    
    <input 
        type="text"
        :id="name"
        class="form-control"
        v-model="dataValue"
        placeholder="YYYY-MM-DD"
        pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
        @change="updateValue" />

</template>

<script>
    export default {
        data(){
            return{
                dataValue: this.formatDate(new Date(this.value))
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
            value: {
                handler: function (val) {
                    this.dataValue = this.formatDate(new Date(val));
                }
            }
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
