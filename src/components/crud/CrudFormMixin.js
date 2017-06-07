export default {
    data(){
        return {
            uniqId: Math.random().toString(36).substring(7) + '_',
            item: this.itemOrg,
            errors: this.errorsOrg
        };
    },

    props: {
        itemOrg: {
            required: true
        },
        errorsOrg: {
            required: true
        },
        baseUri: {
            required: false
        },
        uri: {
            required: false
        },
        options: {
            required: false
        }
    },

    watch: {
        itemOrg: {
            handler: function () {
                this.item = _.clone(this.itemOrg);
                this.afterRead();
            }
            //deep: true,
            //immediate: true
        },
        errorsOrg: {
            handler: function () {
                this.errors = _.clone(this.errorsOrg);
            }
            //deep: true,
            //immediate: true
        }
    },

    methods: {
        updated() {
            this.$emit('change', this.item);
        },

        afterRead(){},

        hasErrors(){
            return ! _.isEmpty(this.errors);
        },

        getError(field){
            if(this.errors && this.errors[field]){
                return this.errors[field][0];
            }
            return null;
        }
    }
};