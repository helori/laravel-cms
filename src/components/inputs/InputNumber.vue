<style scoped>
    
</style>

<template>
    <div class="form-group" :class="{'has-error': error}">
        <label :for="name" class="control-label col-sm-5">{{ label }} :</label>
        <div class="col-sm-7">
            <input
                type="number"
                :id="name"
                :min="min"
                :max="max"
                :step="step"
                class="form-control"
                :placeholder="placeholder"
                v-model="dataValue"
                @change="updateValue" />
            <p class="help-block" v-if="error">{{ error }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                loaded: false,
                dataValue: 0,
                hasFocus: false,
            };
        },

        props: {
            // "value" is required to use v-model on the component
            'value': { 
              default: 0
            },
            'min': { 
              type: Number,
              default: 0
            },
            'max': { 
              type: Number,
              default: 100000000
            },
            'step': { 
              type: Number,
              default: 1
            },
            'placeholder': { 
              type: String,
              default: ''
            },
            'label': {
              type: String,
              default: ''
            },
            'name': {
              type: String,
              default: ''
            },
            'error': {
              type: String,
              default: null
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
