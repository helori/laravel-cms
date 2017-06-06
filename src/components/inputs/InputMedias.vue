<style scoped>
    .input-medias{
        overflow: hidden;
    }
    .input-medias .preview{
        position: relative;
        float: left;
        width: 150px;
        height: 100px;
        overflow: hidden;
        background: repeating-linear-gradient(
            -45deg,
            #666666,
            #666666 10px,
            #444444 10px,
            #444444 20px
        );
    }
    .input-medias .actions{
        margin-left: 180px;
    }
    .input-medias .preview .image{
        z-index: 1;
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: contain;
    }
    .input-medias .preview video, 
    .input-medias .preview iframe{
        z-index: 1;
        position: absolute;
        width: 100%; height: 100%;
        top: 0; bottom: 0;
        left: 0; right: 0;
        background: black;
        border: 0;
        box-shadow: none;
    }
    .input-medias .preview .text-wrapper{
        display: table;
        z-index: 2;
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: #666;
    }
    .input-medias .preview .text-wrapper .text{
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        color: white;
        font-size: 12px;
    }
</style>

<template>
    
    <div class="input-medias">

        <button type="button" class="btn btn-primary" @click="browse()">    
            Sélectionner...
        </button>
        
        <div class="medias">
            <div v-if="dataValue">
                <div class="media" v-for="(media, i) in dataValue">
                    <div class="preview">
                        <div class="image"
                            v-if="media && media.mime && media.mime.indexOf('image') !== -1"
                            :style="'background-image:url(/' + media.filepath + '?' + decache + ')'">
                        </div>
                        <video controls v-else-if="media && media.mime && media.mime.indexOf('video') !== -1">
                            <source :src="'/' + media.filepath + '?' + decache" :type="media.mime" />
                        </video>
                        <div class="text-wrapper" v-else>
                            <div class="text">
                                <div>{{ media.mime }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="actions">
                        <button type="button" class="btn btn-danger btn-block" @click="remove(i)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Browse -->
        <slide-panel ref="mediasPanel">
            <div slot="content">
                
                <div class="row">
                    <div class="col-md-3" v-for="media in medias">
                    <div class="preview" @click="addMedia(media)">
                            <div v-if="media">
                                <div class="image"
                                    v-if="media && media.mime && media.mime.indexOf('image') !== -1"
                                    :style="'background-image:url(/' + media.filepath + '?' + decache + ')'">
                                </div>
                                <video controls v-else-if="media && media.mime && media.mime.indexOf('video') !== -1">
                                    <source :src="'/' + media.filepath + '?' + decache" :type="media.mime" />
                                </video>
                                <div class="text-wrapper" v-else>
                                    <div class="text">
                                        <div>{{ media.mime }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </slide-panel>

    </div>

</template>

<script>
    export default {
        data(){
            return{
                dataValue: this.value,
                decache: new Date().getTime(),
                medias: []
            };
        },

        props: {
            'value': { 
                type: Array
            },
            'queries-base-url': {
                default: '/admin/'
            }
        },

        watch: {
            value: function(val){
                this.dataValue = val;
            }
        },

        mounted(){
            return axios.get(this.queriesBaseUrl + 'medias').then(response => {
                this.medias = response.data;
            }).catch(response => {

            });
        },

        methods: {
            browse(){
                this.$refs.mediasPanel.setOpen(true);
                //$(this.$el).find('.browse-dialog').modal('show');
            },

            remove(i){
                this.dataValue.splice(i, 1);
                this.$emit('input', this.dataValue);
            },

            addMedia(media){
                this.$refs.mediasPanel.setOpen(false);
                this.dataValue.push(media);
                this.$emit('input', this.dataValue);
            }
        }
    }
</script>
