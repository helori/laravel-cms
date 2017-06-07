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
    .library h3{
        text-align: center;
        font-size: 20px;
        font-weight: 300;
        margin: 0 0 30px 0;
    }
    .library .preview-wrapper{
        position: relative;
        cursor: pointer;
        margin-bottom: 30px;
        background: #E0E0E0;
    }
    .library .preview-wrapper:hover{
        background: #f56857;
    }
    .library .preview{
        width: 100%;
        height: 0;
        padding-bottom: 60%;
        float: none;
    }
    .library .bottom{
        height: 80px;
        padding: 10px;
        line-height: 20px;
        text-align: center;
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
                
                <div class="library">
                    <h3>Choisissez dans votre bibliothèque de médias...</h3>
                    <div class="row">
                        <div class="col-md-3" v-for="media in medias">
                            <div class="preview-wrapper" @click="setMedia(media)">
                                <div class="preview">
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
                                <div class="bottom">
                                    <div>{{ media.mime }}</div>
                                    <div v-if="media.size < 1000000">{{ media.size / 1000 | number:2 }} ko</div>
                                    <div v-else>{{ media.size / 1000000 | number:2 }} Mo</div>
                                    <div v-if="media.mime.indexOf('image') !== -1">{{ media.width }} x {{ media.height }} px</div>
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
                type: String,
                default: '/admin/api'
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

            return axios.get(this.queriesBaseUrl + '/media').then(response => {
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
