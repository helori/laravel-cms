<style scoped>
    
</style>

<template>
    <input
        type="email"
        :id="name"
        class="form-control"
        :placeholder="placeholder"
        v-model="dataValue"
        @change="updateValue" />
</template>

<script>
    import inputMixin from '../crud/InputMixin.js'
    export default {
        mixins: [inputMixin]
    }
</script>

