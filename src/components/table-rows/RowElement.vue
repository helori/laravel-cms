<template>
    <tr>
        <td v-for="field in options.table.fields" v-if="field.list">
            <div>{{ item[field.name] }}</div>
        </td>

        <td class="text-right" style="width: 130px">
            <a class="btn btn-default" :href="'/admin/element/' + options.table.id + '/' + item.id">
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
        props: {
            item: {

            },
            options: {

            }
        },
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
