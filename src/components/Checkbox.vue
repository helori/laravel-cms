<style scoped>
    
</style>

<template>
    <div class="checkbox-wrapper" :class="{checked: checked, required: required, disabled: disabled}">
        <label :for="name">
            <input type="checkbox" :name="name" :id="name" :required="required" :disabled="disabled"
                v-model="checked" v-on:change="updateValue()">
            <div class="inside">
                <div class="control">
                    <div class="cursor"></div>
                </div>
            </div>
            <div class="text">
                <div class="inside">
                    <div class="name">{{ label }}</div>
                    <div class="help">{{ help }}</div>
                </div>
            </div>
        </label>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                checked: false
            };
        },

        props: {
            // "value" is required to use v-model on the component
            'value': { 
              type: Boolean,
              default: false
            },
            'label': {
              type: String,
              default: ''
            },
            'help': {
              type: String,
              default: ''
            },
            'name': {
              type: String,
              default: ''
            },
            'required': {
              type: Boolean,
              default: false
            },
            'disabled': {
              type: Boolean,
              default: false
            }
        },

        watch: {
            value: function(val){
                //console.log(this.name + ' updated', val);
                this.checked = val;
            }
        },

        mounted() {
            this.checked = this.value;
        },

        methods: {
            updateValue: function () {
                console.log('checkbox updated', this.checked);
                // required to use v-model on the component :
                this.$emit('input', this.checked);
            }
        }
    }
</script>
