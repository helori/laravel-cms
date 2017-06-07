export default {
    data(){
        return{
            dataValue: this.value,
            uniqId: Math.random().toString(36).substring(7) + '_',
            hasFocus: false
        };
    },

    props: {
        // "value" is required to use v-model on the component
        'value': { 
            default: null
        },
        'placeholder': { 
            type: String,
            default: '',
            required: false
        },
        'name': {
            type: String,
            default: ''
        },
        'error': {
            default: null
        },
        'required': {
            type: Boolean,
            default: false,
            required: false
        },
        'disabled': {
            type: Boolean,
            default: false,
            required: false
        }
    },

    computed: {
        errorMessage: function () {
            if(this.error){
                if(this.error[0]){
                    return this.error[0];
                }
                else{
                    return this.error;
                }
            }
            return '';
        }
    },

    watch: {
        value: {
            handler: function (val) {
                this.dataValue = val;
            }
        }
    },

    methods: {
        updateValue: function () {
            // required to use v-model on the component :
            //console.log('updating', this.name, this.dataValue);
            this.$emit('input', this.dataValue);
        }
    }
}
