<template>
    <tr>
        <td>
            <div>{{ item.name }}</div>
        </td>
        <td>
            <div>{{ item.email }}</div>
        </td>

        <td class="text-right" style="width: 130px">
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
    export default {
        props: [
            'item'
        ],
        methods: {
            update: function (item) {
                this.$emit('update')
            },
            destroy: function (item) {
                this.$emit('destroy')
            }
        },
    }
</script>
