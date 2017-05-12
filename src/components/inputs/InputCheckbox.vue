<style scoped>
    .checkbox-wrapper{
        margin: 0;
        line-height: normal;
    }
    .checkbox-wrapper label{
        display: inline-block;
        vertical-align: top;
        position: relative;
        min-height: 30px;
        margin: 0;
        overflow: hidden;
        cursor: pointer;
        font-weight: 400;
        font-size: 14px;
        color: black;
    }
    .checkbox-wrapper label input{
        position: absolute;
        left: -9999px;
    }
    .checkbox-wrapper label .control{
        position: absolute;
        left: 0; top: 50%;
        margin-top: -15px;
        width: 45px; 
        height: 30px;
        border-radius: 15px;
        background: rgba(0, 0, 0, 0.5);
    }
    .checkbox-wrapper label .control .cursor{
        position: absolute;
        left: 2px;
        top: 2px;
        width: 26px;
        height: 26px;
        background: white;
        border-radius: 50%;
        transition: all 0.1s linear;
    }
    .checkbox-wrapper label .text{
        margin-left: 55px;
        min-height: 30px;
        vertical-align: middle;
        display: table;
    }
    .checkbox-wrapper label .text > .inside{
        display: table-cell;
        min-height: 30px;
        vertical-align: middle;
    }
    .checkbox-wrapper label .text > .inside > .name{
        font-weight: 400;
    }
    .checkbox-wrapper label .text > .inside > .hint{
        font-size: 13px;
    }
    .checkbox-wrapper label .text > .inside a{
        color: black;
        text-decoration: underline;
    }
  
    .checkbox-wrapper.checked label .control{
        background: blue;
    }
    .checkbox-wrapper.checked label .control .cursor{
        left: 17px;
    }
    .checkbox-wrapper.required:not(.checked) label .control{
        background: red;
    }
    .checkbox-wrapper.required:not(.checked) label .text{
        color: red;
    }
    .checkbox-wrapper.disabled.checked label .control{
        background: #DDD;
    }
    .checkbox-wrapper.disabled.checked label .control .cursor{
        background: #F0F0F0;
    }
    .checkbox-wrapper.disabled.checked label .text{
        color: #AAA;
    }
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
              //type: Boolean,
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
                this.checked = val === true || val == 'true' || val == '1';
            }
        },

        mounted() {
            this.checked = this.value === true || this.value == 'true' || this.value == '1';
        },

        methods: {
            updateValue: function () {
                //console.log('checkbox updated', this.checked);
                // required to use v-model on the component :
                this.$emit('input', this.checked);
            }
        }
    }
</script>
