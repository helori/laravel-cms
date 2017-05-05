<style scoped>
    
</style>

<template>
    <div class="radio-wrapper" :class="{disabled: disabled}">
        <label :for="id">
            <input type="radio" 
                :name="name" 
                :id="id" 
                :disabled="disabled" 
                :value="text"
                v-model="localModel"
                @change="updateValue">
            <div class="inside">
                <div class="control">
                    <div class="cursor" v-if="localModel == text"></div>
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
                localModel: '',
                id: ''
            };
        },

        props: {
            // "value" is required to use v-model on the component
            'value': { 
              type: String,
              default: false
            },
            'text': { 
              type: String,
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
            'disabled': {
              type: Boolean,
              default: false
            }
        },

        watch: {
            value: function(val){
                this.localModel = val;
                console.log('radio watching', this.localModel);
            }
        },

        mounted() {
            this.localModel = this.value;
            console.log('radio init', this.localModel, this.text);
            this.id = Math.floor((Math.random() * 1000) + 1) + '-' + this.text;
        },

        methods: {
            updateValue: function (e) {
                console.log('radio updating', this.localModel, e.target.value);
                // required to use v-model on the component :
                this.$emit('input', this.localModel);
            }
        }
    }
</script>
