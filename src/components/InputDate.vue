<style scoped>
    
</style>

<template>
    <!--div class="input-wrapper" :class="{'touched': hasFocus || dataValue, 'has-error': error}">
        <input
            type="date"
            :id="name"
            class="form-control"
            v-model="dataValue"
            @focus="hasFocus = true"
            @blur="hasFocus = false"
            @change="updateValue" />
        <label :for="name">{{ label }}</label>
        <p class="error" v-if="error">{{ error }}</p>
    </div-->

    <div class="form-group" :class="{'has-error': error}">
        <label :for="name" class="control-label col-sm-5">{{ label }} :</label>
        <div class="col-sm-7">
            <input
                type="date"
                :id="name"
                class="form-control"
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
                dataValue: '',
                hasFocus: false
            };
        },

        props: {
            // "value" is required to use v-model on the component
            'value': { 
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
