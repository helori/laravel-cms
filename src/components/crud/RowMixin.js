export default {
    props: {
        item: {
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
        options: {
            type: Object,
            required: false,
            default: function(){
                return {};
            }
        }
    },
    methods: {
        update: function (item) {
            this.$emit('update', item);
        },
        destroy: function (item) {
            this.$emit('destroy', item);
        }
    },
}
