<style scoped>
    
</style>

<template>
    
    <input
        type="text"
        :id="name"
        class="form-control"
        :placeholder="placeholder"
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
            'placeholder': { 
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
                    this.dataValue = val;
                    this.loaded = true;
                }
            }
        },

        mounted() {
            this.dataValue = this.value;
        },

        methods: {
            updateValue: function () {
                // required to use v-model on the component :
                this.$emit('input', this.dataValue);
            }
        }
    }
</script>
