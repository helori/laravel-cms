<style scoped>
    .media{
        margin-top: 5px;
        padding: 5px;
        background: white;
        border-radius: 4px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
        border: 1px solid #ccc;
    }
    .input-medias .preview{
        position: relative;
        float: left;
        width: 150px;
        height: 100px;
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

        <button type="button" class="btn btn-primary btn-block" @click="browse()">    
            Ajouter des fichiers...
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
                        <div class="row">
                            <div class="col-sm-4">
                                <button type="button" class="btn btn-danger btn-block" @click="remove(i)">    
                                    Supprimer
                                </button>
                            </div>
                            <div class="col-sm-8">
                                <div>Type : {{ media.mime }}</div>
                                <div v-if="media.size < 1000000">Poids : {{ media.size / 1000 | number(2) }} ko</div>
                                <div v-else>Poids : {{ media.size / 1000000 | number(2) }} Mo</div>
                                <div v-if="media.mime.indexOf('image') !== -1">Taille : {{ media.width }} x {{ media.height }} px</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Browse -->
        <slide-panel ref="mediasPanel">
            <div slot="content">
                
                <div class="library">
                    <h3>Choisissez dans votre bibliothèque de médias...</h3>
                    <div class="row">
                        <div class="col-md-3" v-for="media in medias">
                            <div class="preview-wrapper" @click="addMedia(media)">
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
                                    <div v-if="media.size < 1000000">{{ media.size / 1000 | number(2) }} ko</div>
                                    <div v-else>{{ media.size / 1000000 | number(2) }} Mo</div>
                                    <div v-if="media.mime.indexOf('image') !== -1">{{ media.width }} x {{ media.height }} px</div>
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
                type: Array,
                default: function(){
                    return [];
                }
            },
            'queries-base-url': {
                default: '/admin/api'
            }
        },

        watch: {
            value: function(val){
                this.dataValue = val;
            }
        },

        mounted(){
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
