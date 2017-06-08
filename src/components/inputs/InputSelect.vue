<style scoped>
    
</style>

<template>
    <select
        :id="name"
        class="form-control"
        v-model="dataValue"
        @change="updateValue">
            <option disabled v-if="hasEmpty" :value="emptyValue">{{ emptyLabel }}</option>
            <option :value="o.value" v-for="o in options">{{ o.label }}</option>
    </select>
</template>

<script>
    import inputMixin from '../crud/InputMixin.js'
    export default {
        mixins: [inputMixin],
        props: {
            'options': { 
              type: Array,
              default: function(){
                return [];
              }
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
              default: ''
            }
        },
    }
</script>
