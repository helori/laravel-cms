<style scoped>
    
</style>

<template>
    <select multiple
        :id="name"
        class="form-control"
        v-model="dataValue"
        @focus="hasFocus = true"
        @blur="hasFocus = false"
        @change="updateValue">
            <option v-for="option in options" :value="option[optionValueKey]">{{ option[optionLabelKey] }}</option>
    </select>
</template>

<script>
    import inputMixin from '../crud/InputMixin.js'
    export default {
        mixins: [inputMixin],
        props: {
            value: { 
                default: function(){
                    return [];
                }
            },
            options: { 
                type: Array,
                default: function(){
                    return [];
                }
            },
            optionLabelKey: {
                type: String,
                default: 'name'
            },
            optionValueKey: {
                type: String,
                default: 'id'
            }
        },
    }
</script>
