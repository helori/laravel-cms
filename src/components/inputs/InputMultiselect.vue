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
                default: []
            }
        },
    }
</script>
