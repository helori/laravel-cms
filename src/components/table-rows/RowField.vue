<template>
    <tr>
        <td>{{ item.title }}</td>
        <td>{{ item.name }}</td>
        <td>{{ item.type }}</td>

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
    import rowMixin from 'vue-crud/src/crud/RowMixin.js'
    export default {
        mixins: [rowMixin]
    }
</script>