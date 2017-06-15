<style scoped>
    .line{
        line-height: 40px;
    }
</style>

<template>
    <div class="row">
        <div class="col-sm-5">
            <div class="line">
                <span class="indicator" :class="{'active': item.published}"></span>
                {{ item.title }}
            </div>
        </div>
        <div class="col-sm-5">
            <div class="line">
                <strong>Collection : </strong>
                <span v-for="collection in item.collections">
                    {{ collection.title  }}
                </span>
            </div>
        </div>
        <div class="col-sm-2 text-right">
            <a class="btn btn-default" :href="'/admin/page/' + item.id">
                <i class="fa fa-edit"></i>
            </a>
            <a class="btn btn-danger" @click="destroy(item)" v-if="item.pages.length == 0">
                <i class="fa fa-trash"></i>
            </a>
        </div>
    </div>
</template>

<script>
    import rowMixin from '../crud/RowMixin.js'
    export default {
        mixins: [rowMixin]
    }
</script>