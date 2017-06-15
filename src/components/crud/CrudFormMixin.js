export default {
    data(){
        return {
            uniqId: Math.random().toString(36).substring(7) + '_',
            item: {},
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

    mounted(){
        this.setItem(this.itemOrg);
    },

    watch: {
        itemOrg: {
            handler: function (item) {
                this.setItem(item);
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
        setItem(item){
            this.item = _.clone(this.itemOrg);
            this.afterRead();
        },

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