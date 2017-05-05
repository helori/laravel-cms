<style scoped>
    
</style>

<template>
    <!--div class="input-wrapper" :class="{'touched': hasFocus || dataValue, 'has-error': error}">
        <select
            :id="name"
            class="form-control"
            v-model="dataValue"
            @focus="hasFocus = true"
            @blur="hasFocus = false"
            @change="updateValue">
                <option v-if="hasEmpty" :value="emptyValue">{{ emptyLabel }}</option>
                <option :value="o.value" v-for="o in options">{{ o.label }}</option>
        </select>
        <label :for="name">{{ label }}</label>
        <p class="error" v-if="error">{{ error }}</p>
    </div-->

    <div class="form-group" :class="{'has-error': error}">
        <label :for="name" class="control-label col-sm-5">{{ label }} :</label>
        <div class="col-sm-7">
            <select
                :id="name"
                class="form-control"
                v-model="dataValue"
                @change="updateValue">
                    <option disabled v-if="hasEmpty" :value="emptyValue">{{ emptyLabel }}</option>
                    <option :value="o.value" v-for="o in options">{{ o.label }}</option>
            </select>
            <p class="help-block" v-if="error">{{ error }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                loaded: false,
                dataValue: '',
                hasFocus: false,
            };
        },

        props: {
            // "value" is required to use v-model on the component
            'value': { 
              type: String,
              default: ''
            },
            'options': { 
              type: Array,
              default: []
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
            },
            'hasEmpty': {
              type: Boolean,
              default: true
            },
            'emptyValue': {
              type: String,
              default: ''
            },
            'emptyLabel': {
              type: String,
              default: 'Choisir...'
            }
        },

        watch: {
            value: function(val){
                if(!this.loaded){
                    //console.log('select init watch', val);
                    this.dataValue = val;
                    this.loaded = true;
                }
            }
        },

        mounted() {
            //console.log('select init mounted', this.value);
            this.dataValue = this.value;
        },

        methods: {
            updateValue: function () {
                // required to use v-model on the component :
                console.log('select changed', this.dataValue);
                this.$emit('input', this.dataValue);
            }
        }
    }
</script>
