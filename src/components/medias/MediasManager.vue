<style scoped>
h1{
    font-size: 30px;
    font-weight: 300;
    margin: 0 0 5px 0;
}
.displayed{
    font-size: 16px;
    font-weight: 300;
    font-style: italic;
    margin: 0 0 15px 0;   
}
.no-results{
    padding: 40px 0;
}
.search-bar, .progress-wrapper, .alert{
    margin: 15px 0;
}
.progress{
    margin: 0;
}
input.file-input{
    position: absolute;
    left: -9999px;
}
input.file-input + label {
    margin: 0;
    padding: 30px;
    cursor: pointer;
    background: rgba(0, 0, 0, 0.1);
    border: 1px dashed rgba(0, 0, 0, 0.3);
    text-align: center;
    font-style: italic;
    font-weight: 400;
    background: #E0E0E0;
}
input.file-input + label:hover,
input.file-input + label.disabled {
    background: #ff9070;
    color: white;
}
.cell-preview{
    width: 200px;
}
</style>

<template>

    <div class="medias-manager">

        <div class="row">
            <div class="col-md-3">

                <h1>Medias</h1>
                <div class="displayed">
                    <span v-if="loaded && pagination.total > 0">
                        Showing {{ (pagination.current_page - 1) * pagination.per_page + 1 }}
                        to {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }} / {{ pagination.total }}
                    </span>
                    <span v-else-if="loaded && pagination.total == 0">
                        No media found
                    </span>
                    <span v-else>
                        Loading...
                    </span>
                </div>

                <input type="file" multiple
                    accept="image/jpeg,image/png,application/pdf,video/mp4,video/m4v"
                    :id="'file-input-' + id" 
                    :disabled="uploadStatus == 'uploading'"
                    class="file-input">
                <label :for="'file-input-' + id" class="table filedrop" :class="{'disabled': uploadStatus == 'uploading'}">
                    <div class="cell">
                        Drag & drop / browse files...
                    </div>
                </label>

                <div class="progress-wrapper" v-if="uploadStatus == 'uploading'">
                    <div class="progress-cell">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" :style="'width:' + (100 * uploadProgress / uploadTotal) + '%'">
                                <span class="sr-only">{{100 * uploadProgress / uploadTotal}}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-warning btn-block" @click="cancel()" v-if="uploadStatus == 'uploading'">
                    <i class="fa fa-close"></i> Cancel
                </button>

                <div v-if="uploadError" class="alert alert-danger text-center">
                    Erreur {{ uploadError.response.status }} : {{ uploadError.response.statusText }}
                </div>

                <div v-if="uploadStatus == 'done'" class="alert alert-success text-center">
                    Files uploaded successfully
                </div>

                <div class="search-bar">
                    <input-text
                        v-model="search.text"
                        name="search"
                        placeholder="Search..."
                        :autofocus="true">
                    </input-text>
                </div>
                
            </div>
            <div class="col-md-9">

                <panel>
                    <div slot="body">

                        <div class="text-right">
                            <!--pagination :pagination="pagination" @change="loadPage"></pagination-->
                        </div>

                        <div v-if="pagination.total === 0" class="no-results text-center">
                            No media found, but you should hit the "browse" button pretty soon!
                        </div>

                        <div class="text-center">
                            <pagination :pagination="pagination" @change="loadPage"></pagination>
                        </div>

                        <div class="table-responsive" v-show="pagination.total > 0">
                            <table class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>Preview</th>
                                        <th>Details</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, idx) in pagination.data">
                                        <td class="cell-preview">
                                            <media-thumb
                                                :media="item">
                                            </media-thumb>
                                        </td>
                                        <td>    
                                            <div>
                                                <span class="lab">Title :</span>
                                                <span class="text-info">{{ item.title }}</span>
                                            </div>
                                            <div>
                                                <span class="lab">File :</span>
                                                <span class="text-info">{{ item.filename }}</span>
                                            </div>
                                            <div>
                                                <span class="lab">Type :</span>
                                                <span class="text-info">{{ item.mime }}</span>
                                            </div>
                                            <div>
                                                <span class="lab">Size :</span>
                                                <span class="text-info">
                                                    <span v-if="item.size < 1000000">{{ item.size / 1000 }} ko</span>
                                                    <span v-else>{{ item.size / 1000000 }} Mo</span>
                                                </span>
                                            </div>
                                            <div v-if="item.mime.indexOf('image') !== -1">
                                                <span class="lab">Dimensions :</span>
                                                <span class="text-info">{{ item.width }} x {{ item.height }} px</span>
                                            </div>
                                        </td>

                                        <td class="actions text-right">

                                            <div class="btn-group">

                                                <button type="button" @click="download(item)" class="btn btn-default">
                                                    <i class="fa fa-download"></i>
                                                </button>

                                                <button type="button" @click="openUpdate(item)" class="btn btn-default">
                                                    <i class="fa fa-edit"></i>
                                                </button>

                                                <button type="button" @click="openDestroy(item)" class="btn btn-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                                
                                            </div>
                                            
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center">
                            <pagination :pagination="pagination" @change="loadPage"></pagination>
                        </div>

                    </div>
                </panel>

            </div>
        </div>

        <dialog-destroy
            ref="destroyDialog"
            title="Delete file"
            message="Are you sure ?"
            :status="destroyStatus"
            :error="destroyError"
            @destroy="destroy">
        </dialog-destroy>

        <media-updater 
            ref="mediaUpdater"
            :uri-prefix="uriPrefix"
            @updated="read()">
        </media-updater>

    </div>
</template>

<script>

    import inputText from 'vue-crud/src/inputs/InputText.vue'
    import pagination from 'vue-crud/src/widgets/Pagination.vue'
    import panel from 'vue-crud/src/widgets/Panel.vue'
    import dialogDestroy from 'vue-crud/src/widgets/DialogDestroy.vue'

    import mediaThumb from './MediaThumb.vue';
    import mediaUpdater from './MediaUpdater.vue';
    //const MediaUpdater = () => import('./MediaUpdater.vue');

    export default {

        components:{
            inputText: inputText,
            pagination: pagination,
            panel: panel,
            dialogDestroy: dialogDestroy,

            mediaThumb: mediaThumb,
            mediaUpdater: mediaUpdater
        },

        data(){
            return{
                id: Math.floor(Math.random()*(9999-1000+1)+1000),
                files: null,
                search: {
                    text: '',
                },
                
                pagination: {},
                page: 1,

                selectAll: false,
                selected: [],

                loaded: false,
                //readStatus: 'none',

                uploadSource: null,
                uploadStatus: 'none',
                uploadProgress: 0,
                uploadTotal: 0,
                uploadError: null,

                destroyStatus: 'none',
                destroyError: null
            };
        },

        props: {
            uriPrefix: {
                type: String,
                required: false,
                default: ''
            }
        },

        watch: {
            search: {
                handler(){
                    this.page = 1;
                    this.read();
                },
                deep: true
            },
        },

        mounted() {

            var self = this;

            // -------------------------------------------------------
            //  Init browse button
            // -------------------------------------------------------
            $(this.$el).on('change', '.file-input', function(e){
                self.files = e.target.files;
                self.upload();
            }).on('click', '.file-input', function(e){
                this.value = null;
            });

            // -------------------------------------------------------
            //  Init cancel token
            // -------------------------------------------------------
            var CancelToken = axios.CancelToken;
            this.uploadSource = CancelToken.source();

            // -------------------------------------------------------
            //  read items
            // -------------------------------------------------------
            this.read();

            // -------------------------------------------------------
            //  Prevent from openning file in browser on drop
            // -------------------------------------------------------
            window.addEventListener("dragover", function(e){
                e.preventDefault();
            }, false);

            window.addEventListener("drop", function(e){
                e.preventDefault();
            }, false);

            // -------------------------------------------------------
            //  Init drag and drop
            // -------------------------------------------------------
            $(this.$el).find('.filedrop').on('drop', function(e){
                e.preventDefault();
                e.stopPropagation();
                //console.log('files dropped', e.originalEvent.dataTransfer.files);
                self.files = e.originalEvent.dataTransfer.files;
                self.upload();
            });
        },

        methods: {

            read: function()
            {
                var url = this.uriPrefix + '/api/media?page=' + this.page;
                url += '&text=' + this.search.text;
                this.loaded = false;

                axios.get(url).then(response => {

                    this.pagination = response.data;
                    this.loaded = true;

                }).catch(response => {

                    this.loaded = true;

                });
            },

            loadPage(p){
                this.page = p;
                this.read();
            },

            upload: function()
            {
                var self = this;

                this.uploadStatus = 'uploading';
                this.uploadProgress = 0;
                this.uploadTotal = 0;
                this.uploadError = null;

                var formData = new FormData();
                for(var i=0; i<this.files.length; ++i){
                    formData.append('file-' + i, this.files[i], this.files[i].name);
                }

                var config = {
                    onUploadProgress: function(e) {
                        console.log('upload progress', e);
                        if (e.lengthComputable) {
                            self.uploadProgress = e.loaded;
                            self.uploadTotal = e.total;
                        }
                    },
                    //cancelToken: this.uploadSource
                };

                axios.post(this.uriPrefix + '/api/media', formData, config).then(response => {
                    
                    this.uploadStatus = 'done';
                    this.read();
                    
                }).catch(response => {
                    
                    this.uploadStatus = 'none';
                    this.uploadError = response.data;
                });
            },

            cancel: function()
            {
                if(this.uploadSource){
                    console.log('upload cancel');
                    this.uploadSource.cancel();
                }else{
                    console.log('Cannot cancel', this.uploadSource);
                }
            },

            openDestroy(item){
                
                this.destroyItem = item;
                this.destroyError = null;
                this.destroyStatus = null;

                this.$refs.destroyDialog.open();
            },

            destroy() {

                this.destroyStatus = 'loading';
                
                axios.delete(this.uriPrefix + '/api/media/' + this.destroyItem.id).then(response => {

                    this.read();
                    this.destroyStatus = 'success';
                    this.$refs.destroyDialog.close();

                }).catch(response => {

                    this.destroyStatus = 'error';
                    this.destroyError = response.data;

                });

            },

            download(item){
                window.location.href = this.uriPrefix + '/api/media/' + item.id + '/download';
            },

            openUpdate(item){
                this.$refs.mediaUpdater.openUpdateDialog(item);
            },
        }
    }
</script>
