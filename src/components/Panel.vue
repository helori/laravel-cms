<style scoped>
    
</style>

<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <slot name="title"></slot>
            </h4>
        </div>
        <div class="panel-body">
            <slot name="body"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        
    }
</script>
