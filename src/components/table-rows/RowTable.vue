<template>
    <tr>
        <td>
            <div>{{ item.name }}</div>
        </td>
        <td>
            <div>
                <span class="lab">Nom de la table : </span>
                <span class="text-info">{{ item.table }}</span>
            </div>
            <div>
                <span class="lab">Nombre de champs : </span>
                <span class="text-info">{{ item.fields.length }}</span>
            </div>
            <div>
                <span class="lab">Type de table : </span>
                <span class="text-info">{{ item.multiple ? 'éléments multiples' : 'un seul élément' }}</span>
            </div>
        </td>

        <td class="text-right">
            <a class="btn btn-default" @click="update(item)">
                <i class="fa fa-table"></i> Table
            </a>
            <a class="btn btn-default" :href="'/admin/table/' + item.id + '/fields'">
                <i class="fa fa-list-alt"></i> Champs
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
