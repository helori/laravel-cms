<style scoped>
    
</style>

<template>
    
    <div class="form-group" :class="{'has-error': error}">
        <label :for="name" class="control-label col col-sm-4">{{ label }} :</label>
        <div class="col col-sm-8">
            <slot name="input"></slot>
            <p class="help-block" v-if="error">{{ error }}</p>
        </div>
    </div>

</template>

<script>
    export default {
        props: {
            'label': {
                type: String,
                default: ''
            },
            'name': {
                type: String,
                default: ''
            },
            'error': {
                default: null
            }
        },
    }
</script>
