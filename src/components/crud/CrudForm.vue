<style scoped>
    
</style>

<template>
    <div v-if="loaded">

        <component
            ref="formComp"
            :is="formComponent"
            :item-org="item"
            :errors-org="errors"
            :base-uri="baseUri"
            :uri="uri"
            :options="options"
            @change="update">
        </component>

    </div>
</template>
                        
<script>
    export default {
        data() {
            return {
                item: {},
                errors: {},
                readyToLoad: true,
                loaded: false
            };
        },
        props: {
            id: {
                type: Number,
                required: true,
                default: 0
            },
            baseUri: {
                type: String,
                required: true
            },
            uri: {
                type: String,
                required: true
            },
            formComponent: {
                required: true
            },
            options: {
                required: false
            }
        },
        watch: {
            readyToLoad: function(value){
                //console.log('id changed', value);
                if(this.readyToLoad && !this.loaded && this.id !== 0){
                    this.read();
                }
            },
            id: function(value){
                //console.log('id changed', value);
                if(this.readyToLoad && !this.loaded && this.id !== 0){
                    this.read();
                }
            }
        },
        mounted() {
            //console.log('mounted', this.uri, this.id);
            if(this.readyToLoad && !this.loaded && this.id !== 0){
                this.read().then(response => {
                    if(this.initCallback){
                        this.initCallback();
                    }
                });
            }
        },
        methods: {
            read: function(){
                this.$emit('appstate', 'loading', 'chargement');
                var promise = axios.get(this.baseUri + this.uri + '/' + this.id).then(response => {
                    //console.log('read', this.baseUri + this.uri, this.id, response.data);
                    this.item = response.data;
                    this.loaded = true;
                    this.$emit('appstate', 'success', 'chargé');
                    //this.validate();
                }).catch(response => {
                    this.$emit('appstate', 'error', 'erreur de chargement');
                });
                return promise;
            },
            update: function(item){
                this.errors = {};
                this.$emit('appstate', 'loading', 'mise à jour');
                axios.put(this.baseUri + this.uri + '/' + item.id, item).then(response => {
                    //console.log('update', this.baseUri + this.uri, this.id, this.item, response.data);
                    this.item = response.data;
                    this.$emit('appstate', 'success', 'mis à jour');
                    this.$emit('updated', this.item);
                    if(this.updateCallback){
                        this.updateCallback();
                    }
                    //this.validate();
                }).catch(response => {
                    //this.validate();
                    this.errors = response.body;
                    this.$emit('appstate', 'error', 'erreur de mise à jour');
                    console.log(response);
                });
            },
            /*validate(){
                this.$emit('appstate', 'loading', 'validation');
                axios.post(this.baseUri + this.uri + '/validate/' + this.id, this.item).then(response => {
                    //console.log('validate', this.baseUri + this.uri, this.id, response.data);
                    this.errors = response.data;
                    this.$emit('appstate', 'success', 'validation effectuée');
                }).catch(response => {
                    this.$emit('appstate', 'error', 'erreur de validation');
                });
            },*/

            hasErrors(){
                return ! _.isEmpty(this.errors);
            },

            getError(field){
                if(this.errors[field]){
                    return this.errors[field][0];
                }
                return null;
            }
        }
    };
</script>