<style scoped>
.media{
    
}
.thumb{
    float: left;
    width: 150px;
}
.actions{
    margin-left: 160px;
}
.item{
    margin: 5px 0 0 0;
}
.row.narrow{
    margin-left: -5px;
    margin-right: -5px;
}
.row.narrow > .col{
    padding-left: 5px;
    padding-right: 5px;
}
</style>

<template>

    <div>

        <div class="media" v-if="!multiple && dataValue">

            <div class="thumb">
                <media-thumb
                    :media="dataValue[0]">
                </media-thumb>
            </div>
            
            <div class="actions">
                <div class="row">
                    <div class="col-sm-5">
                        <button type="button" class="btn btn-primary btn-block" @click="browse()">    
                            Browse...
                        </button>
                        <button type="button" class="btn btn-danger btn-block" @click="remove()" v-if="dataValue[0]">
                            Delete
                        </button>
                    </div>
                    <!--div class="col-sm-8" v-if="dataValue[0]">
                        <media-spec
                            :media="dataValue[0]">
                        </media-spec>
                    </div-->
                </div>
            </div>

        </div>

        <div class="media" v-else-if="multiple && dataValue">

            <button type="button" class="btn btn-primary btn-block" @click="browse()">    
                Browse...
            </button>

            <div class="row narrow">
                <div class="col col-sm-4" v-for="m in dataValue">
                    <div class="item">
                        <media-thumb
                            :media="m">
                        </media-thumb>
                        <button type="button" class="btn btn-danger btn-block" @click="remove(m)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

        </div>

        <media-browser 
            ref="mediaBrowser"
            v-model="dataValue"
            :uri-prefix="uriPrefix"
            :multiple="multiple"
            @input="update">
        </media-browser>

    </div>

</template>

<script>

    import mediaThumb from './MediaThumb.vue';
    import mediaSpec from './MediaSpec.vue';
    import mediaBrowser from './MediaBrowser.vue';

    export default {

        components: {
            mediaThumb: mediaThumb,
            mediaSpec: mediaSpec,
            mediaBrowser: mediaBrowser
        },

        data(){
            return{
                dataValue: this.value
            }
        },

        props: {
            // "value" is required to use v-model on the component
            value: { 
                default: null
            },
            name: {
                type: String,
                default: ''
            },
            error: {
                default: null
            },
            multiple: {
                type: Boolean,
                required: false,
                default: false
            },
            uriPrefix: {
                type: String,
                required: false,
                default: ''
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

            browse(){
                this.$refs.mediaBrowser.open();
            },

            update(selected){
                this.dataValue = selected;
                this.$emit('input', this.dataValue);
                this.$refs.mediaBrowser.close();
            },

            remove(media){
                if(this.multiple && media){
                    var idx = _.findIndex(this.dataValue, function(m) { return m.id == media.id; });
                    if(idx !== -1){
                        this.dataValue.splice(idx, 1);
                    }
                }else{
                    this.dataValue = [];
                }
                this.$emit('input', this.dataValue);
            }
        }
    }
</script>
