<style scoped>
    
</style>

<template>
    <input type="password"
        :id="name"
        class="form-control"
        :placeholder="placeholder"
        v-model="dataValue"
        @focus="hasFocus = true"
        @blur="hasFocus = false"
        @change="updateValue" />
</template>

<script>
    import inputMixin from '../crud/InputMixin.js'
    export default {
        mixins: [inputMixin]
    }
</script>

