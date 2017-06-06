<style scoped>
    .input-media{
        overflow: hidden;
    }
    .input-media .preview{
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
    .input-media .actions{
        margin-left: 180px;
    }
    .input-media .preview .image{
        z-index: 1;
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: contain;
    }
    .input-media .preview video, 
    .input-media .preview iframe{
        z-index: 1;
        position: absolute;
        width: 100%; height: 100%;
        top: 0; bottom: 0;
        left: 0; right: 0;
        background: black;
        border: 0;
        box-shadow: none;
    }
    .input-media .preview .text-wrapper{
        display: table;
        z-index: 2;
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: #666;
    }
    .input-media .preview .text-wrapper .text{
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        color: white;
        font-size: 12px;
    }
</style>

<template>
    
    <div class="input-media">
        
        <div class="preview">
            <div v-if="dataValue">
                <div class="image"
                    v-if="dataValue && dataValue.mime && dataValue.mime.indexOf('image') !== -1"
                    :style="'background-image:url(/' + dataValue.filepath + '?' + decache + ')'">
                </div>
                <video controls v-else-if="dataValue && dataValue.mime && dataValue.mime.indexOf('video') !== -1">
                    <source :src="'/' + dataValue.filepath + '?' + decache" :type="dataValue.mime" />
                </video>
                <div class="text-wrapper" v-else>
                    <div class="text">
                        <div>{{ dataValue.mime }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="actions">

            <button type="button" class="btn btn-primary" @click="browse()">    
                Sélection...
            </button>
            <button type="button" class="btn btn-danger" @click="remove()" v-if="dataValue">    
                Supprimer
            </button>
        </div>

        <!-- Browse -->
        <slide-panel ref="mediasPanel">
            <div slot="content">
                
                <div class="row">
                    <div class="col-md-3" v-for="media in medias">
                    <div class="preview" @click="setMedia(media)">
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

        <!--div class="modal fade browse-dialog" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Choisir un média</h4>
                    </div>
                    
                    <div class="modal-body">
                        
                        <div class="row">
                            <div class="col-md-3" v-for="media in medias">
                            <div class="preview" @click="setMedia(media)">
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

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Annuler
                        </button>
                    </div>

                </div>
            </div>
        </div-->
        <!-- End Browse -->

    </div>

</template>

<script>
    export default {
        data(){
            return{
                dataValue: null,
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
                if(val){
                    this.dataValue = val[0];
                }
            }
        },

        mounted(){
            if(this.value){
                this.dataValue = this.value[0];
            }

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

            remove(){
                this.dataValue = null;
                this.$emit('input', []);
            },

            setMedia(media){
                this.$refs.mediasPanel.setOpen(false);
                this.dataValue = media;
                this.$emit('input', [this.dataValue]);
            }
        }
    }
</script>
