<style scoped>
    
</style>

<template>
    <div>

        <table class="table">
            <thead>
                <tr>
                    <th v-for="header in options['headers']">{{ header }}</th>
                    <th class="text-right">
                        
                    </th>
                </tr>
            </thead>
            <tbody v-for="item in items">
                <tr :is="rowComponent"
                    :item="item"
                    :base-uri="baseUri"
                    :uri="uri"
                    :options="options"
                    @update="update"
                    @destroy="destroy">
                </tr>
            </tbody>
        </table>
        
    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                items: [],
                createForm: {},
                updateForm: {},
                createErrors: [],
                updateErrors: []
            };
        },

        /*
         * The component's props.
         */
        props: {
            itemsOrg: {
                required: true
            },
            baseUri: {
                type: String,
                required: true
            },
            uri: {
                type: String,
                required: true
            },
            rowComponent: {
                required: true
            },
            options: {
                required: false
            }
        },

        watch: {
            itemsOrg(items){
                this.items = items;
            }
        },

        methods: {
            update: function (item) {
                this.$emit('update', item);
            },

            destroy: function (item) {
                this.$emit('destroy', item);
            }
        }
    }
</script>
