<template>
    <tr>
        <slot name="first-cell"></slot>
        <td>{{ item.id }}</td>
        <td>{{ item.parent_id }}</td>
        <td>{{ item.title }}</td>
        <td>{{ item.slug }}</td>
        <td>
            <div v-for="collection in item.collections">
                {{ collection.title }}
            </div>
        </td>

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