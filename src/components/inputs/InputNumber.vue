<style scoped>
    
</style>

<template>
    <input
        type="number"
        :id="name"
        :min="min"
        :max="max"
        :step="step"
        class="form-control"
        :placeholder="placeholder"
        v-model="dataValue"
        @change="updateValue" />
</template>

<script>
    import inputMixin from '../crud/InputMixin.js'
    export default {
        mixins: [inputMixin],
        props: {
            'min': { 
              type: Number,
              default: 0
            },
            'max': { 
              type: Number,
              default: 100000000
            },
            'step': { 
              type: Number,
              default: 1
            }
        },
    }
</script>
