<style scoped>
    .line{
        line-height: 40px;
    }
</style>

<template>
    <div class="row">
        <div class="col-sm-8">
            <div class="line">
                <span class="indicator" :class="{'active': item.published}"></span>
                {{ item.title }}
            </div>
        </div>
        <div class="col-sm-4 text-right">
            <a class="btn btn-default" :href="'/admin' + uri + '/' + item.id">
                <i class="fa fa-edit"></i>
            </a>
            <a class="btn btn-danger" @click="destroy(item)">
                <i class="fa fa-trash"></i>
            </a>
        </div>
    </div>
</template>

<script>
    import rowMixin from 'vue-crud/src/crud/RowMixin.js'
    export default {
        mixins: [rowMixin]
    }
</script>