<style scoped>
    
</style>

<template>
    
    <textarea
        style="resize:vertical; min-height:150px;"
        type="text"
        :id="name"
        class="form-control"
        :placeholder="placeholder"
        v-model="dataValue"
        @change="updateValue"></textarea>

</template>

<script>
    import inputMixin from '../crud/InputMixin.js'
    export default {
        mixins: [inputMixin]
    }
</script>
