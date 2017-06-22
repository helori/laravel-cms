<template>
    <tr>
        <td>{{ item.title }}</td>

        <td class="text-right">
            <a class="btn btn-default" @click="update(item)">
                <i class="fa fa-edit"></i>
            </a>
            <a class="btn btn-danger" @click="destroy(item)">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
</template>

<script>
    import rowMixin from '../crud/RowMixin.js'
    export default {
        mixins: [rowMixin]
    }
</script>