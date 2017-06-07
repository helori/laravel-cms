<style scoped>
    
</style>

<template>
    <input
        type="tel"
        :id="name"
        class="form-control"
        :placeholder="placeholder"
        pattern=".{10}"
        v-model="dataValue"
        @change="updateValue" />
</template>

<script>
    import inputMixin from '../crud/InputMixin.js'
    export default {
        mixins: [inputMixin]
    }
</script>
