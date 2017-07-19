<style scoped>
    .medias-manager input.file-input{
        position: absolute;
        left: -9999px;
    }
    .medias-manager label p{
        margin: 0;
    }
    .medias-manager input.file-input + label {
        padding: 30px;
        cursor: pointer;
        background: rgba(0, 0, 0, 0.1);
        color: white;
        border: 3px dashed white;
        text-align: center;
        font-style: italic;
        font-weight: 400;
        background: #f56857;
    }
    .medias-manager input.file-input + label:hover,
    .medias-manager input.file-input + label.disabled {
        background: #ff9070;
    }

    .cell-preview{
        min-width: 150px;
    }

    .medias-manager .preview{
        position: relative;
        height: 0;
        padding-bottom: 60%;
        overflow: hidden;
        background: repeating-linear-gradient(
            -45deg,
            #666666,
            #666666 10px,
            #444444 10px,
            #444444 20px
        );
    }
    .medias-manager .preview .image{
        z-index: 1;
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: contain;
    }
    .medias-manager .preview video, 
    .medias-manager .preview iframe{
        z-index: 1;
        position: absolute;
        width: 100%; height: 100%;
        top: 0; bottom: 0;
        left: 0; right: 0;
        background: black;
        border: 0;
        box-shadow: none;
    }
    .medias-manager .preview .text-wrapper{
        display: table;
        z-index: 2;
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: #666;
    }
    .medias-manager .preview .text-wrapper .text{
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        color: white;
        font-size: 12px;
    }
</style>

<template>

    <div class="medias-manager">

        <div class="row">

            <div class="col-sm-6">
                <input type="file" multiple
                    accept="image/jpeg,image/png,application/pdf,video/mp4,video/m4v"
                    :id="'file-input-' + id" 
                    :disabled="upload_state == 'uploading'"
                    class="file-input">
                <label :for="'file-input-' + id" class="table filedrop" :class="{'disabled': upload_state == 'uploading'}">
                    <div class="cell">
                        <strong>Charger de nouveaux fichiers</strong>
                        <p>Vous pouvez glisser-déposer vos fichiers dans cette zone <br>(ou cliquer ici pour en sélectionner)</p>
                    </div>
                </label>
            </div>

            <div class="col-sm-6">

                <div class="progress-wrapper" v-if="upload_state == 'uploading'">
                    <div class="progress-cell">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" :style="'width:' + (100 * upload_progress / upload_total) + '%'">
                                <span class="sr-only">{{100 * upload_progress / upload_total}}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-warning btn-block" @click="cancel()" v-if="upload_state == 'uploading'">
                    <i class="fa fa-close"></i> Annuler le chargement
                </button>

                <div v-if="upload_error" class="alert alert-danger">
                    Erreur {{ upload_error.response.status }} : {{ upload_error.response.statusText }}
                </div>

                <div v-if="upload_state == 'done'" class="alert alert-success">
                    <strong>Vos fichiers sont chargés !</strong>
                </div>

                <button type="button" class="btn btn-danger btn-block" @click="deleteDialog.modal('show')" v-if="hasSelection()">
                    <i class="fa fa-trash"></i> Supprimer les fichiers sélectionnés
                </button>

            </div>
        </div>

        <div class="text-right">
            <nav aria-label="Page navigation" v-show="pagination.total > pagination.per_page">
                <ul class="pagination">
                    <li v-if="pagination.current_page == 1" class="disabled">
                        <span>
                            <span aria-hidden="true">&laquo;</span>
                        </span>
                    </li>
                    <li v-else>
                        <a @click="loadPage(page - 1)" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li v-for="p in pagination.last_page" :class="{'active': p === pagination.current_page}">
                        <a @click="loadPage(p)" v-if="p !== pagination.current_page">{{ p }}</a>
                        <span v-else>{{ p }}</span>
                    </li>
                    <li v-if="pagination.current_page == pagination.last_page" class="disabled">
                        <span>
                            <span aria-hidden="true">&raquo;</span>
                        </span>
                    </li>
                    <li v-else>
                        <a @click="loadPage(page + 1)" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>
                        <input-checkbox
                            name="select-all"
                            v-model="selectAll"
                            label=""
                            @input="toggleAll()">
                        </input-checkbox>
                    </th>
                    <th>Aperçu</th>
                    <th>Infos</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, idx) in items">
                    <td>
                        <input-checkbox
                            :name="item.id + '-selected'"
                            label=""
                            v-model="item.selected"
                            @input="toggle(item)">
                        </input-checkbox>
                    </td>
                    <td class="cell-preview">
                        <div class="preview">
                            <div class="image"
                                v-if="item.mime.indexOf('image') !== -1"
                                :style="'background-image:url(' + assetsBaseUrl + item.filepath + '?' + item.decache + ')'">
                            </div>
                            <video controls v-else-if="item.mime.indexOf('video') !== -1">
                                <source :src="assetsBaseUrl + item.filepath + '?' + item.decache" :type="item.mime" />
                            </video>
                            <div class="text-wrapper" v-else>
                                <div class="text">
                                    <div>{{ item.mime }}</div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>    
                        <div>
                            <span class="lab">Titre :</span>
                            <span class="text-info">{{ item.title }}</span>
                        </div>
                        <div>
                            <span class="lab">Fichier :</span>
                            <span class="text-info">{{ item.filename }}</span>
                        </div>
                        <div>
                            <span class="lab">Type :</span>
                            <span class="text-info">{{ item.mime }}</span>
                        </div>
                        <div>
                            <span class="lab">Poids :</span>
                            <span class="text-info">
                                <span v-if="item.size < 1000000">{{ item.size / 1000 | number(1) }} ko</span>
                                <span v-else>{{ item.size / 1000000 | number(1) }} Mo</span>
                            </span>
                        </div>
                        <div v-if="item.mime.indexOf('image') !== -1">
                            <span class="lab">Taille :</span>
                            <span class="text-info">{{ item.width }} x {{ item.height }} px</span>
                        </div>
                    </td>
                    <td class="text-right">
                        <button 
                            type="button"
                            class="btn btn-default"
                            @click="download(item)">
                            <i class="fa fa-download"></i> Récupérer
                        </button>
                        <button 
                            type="button"
                            class="btn btn-default"
                            @click="openEditor(item)">
                            <i class="fa fa-edit"></i> Modifier
                        </button>
                        <button 
                            type="button"
                            class="btn btn-danger"
                            @click="destroy(item)">
                            <i class="fa fa-trash"></i> Supprimer
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="text-center">
            <nav aria-label="Page navigation" v-show="pagination.total > pagination.per_page">
                <ul class="pagination">
                    <li v-if="pagination.current_page == 1" class="disabled">
                        <span>
                            <span aria-hidden="true">&laquo;</span>
                        </span>
                    </li>
                    <li v-else>
                        <a @click="loadPage(page - 1)" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li v-for="p in pagination.last_page" :class="{'active': p === pagination.current_page}">
                        <a @click="loadPage(p)" v-if="p !== pagination.current_page">{{ p }}</a>
                        <span v-else>{{ p }}</span>
                    </li>
                    <li v-if="pagination.current_page == pagination.last_page" class="disabled">
                        <span>
                            <span aria-hidden="true">&raquo;</span>
                        </span>
                    </li>
                    <li v-else>
                        <a @click="loadPage(page + 1)" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <media-updater 
            ref="mediaUpdater"
            :queries-base-url="queriesBaseUrl"
            :assets-base-url="assetsBaseUrl"
            @updated="refresh()">
        </media-updater>

        <!-- Delete Dialog -->
        <div class="modal fade delete-dialog"tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Supprimer le fichier</h4>
                    </div>
                    
                    <div class="modal-body">
                        Les fichiers sélectionnés sont sur le point d'être supprimés.
                        Cette opération est irréversible. Êtes-vous bien certain(e) ?

                        <div v-if="delete_error" class="alert alert-danger">
                            {{ delete_error }}
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal" v-if="delete_state == 'none'">
                            <i class="fa fa-close"></i> Annuler
                        </button>
                        <button type="button" 
                            class="btn btn-danger" 
                            @click="deleteMedias()"
                            :disabled="delete_state == 'pending'">

                            <span v-if="delete_state == 'none'">
                                Oui, supprimer !
                            </span>
                            <span v-if="delete_state == 'pending'">
                                <i class="fa fa-spinner fa-spin"></i> Suppression en cours...
                            </span>

                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data(){
            return{
                id: Math.floor(Math.random()*(9999-1000+1)+1000),
                files: null,
                
                items: [],
                pagination: {},
                page: 1,

                selectAll: false,
                selected: [],

                upload_source: null,
                upload_state: 'none',
                upload_progress: 0,
                upload_total: 0,
                upload_error: null,

                deleteDialog: null,
                delete_state: 'none',
                delete_error: null
            };
        },

        props: {
            queriesBaseUrl: {
                type: String,
                required: true,
                default: ''
            },
            assetsBaseUrl: { 
                type: String,
                required: true,
                default: ''
            }
        },

        mounted() {
            var self = this;
            this.deleteDialog = $(this.$el).find('.delete-dialog');
            
            // -------------------------------------------------------
            //  Init browse button
            // -------------------------------------------------------
            $(this.$el).on('change', '.file-input', function(e){
                self.files = e.target.files;
                self.upload();
                //self.value = null;
            }).on('click', '.file-input', function(e){
                this.value = null;
            });

            // -------------------------------------------------------
            //  Init cancel token
            // -------------------------------------------------------
            var CancelToken = axios.CancelToken;
            this.upload_source = CancelToken.source();

            // -------------------------------------------------------
            //  Refresh items
            // -------------------------------------------------------
            this.refresh();

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

            loadPage(p){
                this.page = p;
                this.refresh();
            },

            openEditor(item){
                this.$refs.mediaUpdater.openUpdateDialog(item);
            },

            refresh: function()
            {
                var url = this.queriesBaseUrl + 'media?page=' + this.page;
                return axios.get(url).then(response => {
                    this.items = response.data.data;
                    this.pagination = response.data;
                    this.items = _.forEach(this.items, function(item) {
                        item.selected = false;
                    });
                }).catch(response => {
                    
                });
            },

            upload: function()
            {
                var self = this;

                this.upload_state = 'uploading';
                this.upload_progress = 0;
                this.upload_total = 0;
                this.upload_error = null;

                var formData = new FormData();
                for(var i=0; i<this.files.length; ++i){
                    formData.append('file-' + i, this.files[i], this.files[i].name);
                }

                var config = {
                    onUploadProgress: function(e) {
                        console.log('upload progress', e);
                        if (e.lengthComputable) {
                            self.upload_progress = e.loaded;
                            self.upload_total = e.total;
                        }
                    },
                    //cancelToken: this.upload_source
                };

                axios.post(this.queriesBaseUrl + 'media', formData, config).then(response => {
                    //console.log('upload success', response);
                    self.upload_state = 'done';
                    self.upload_request = null;
                    for(var i=0; i<response.data.length; ++i){
                        this.items.push(response.data[i]);
                    }
                }).catch(response => {
                    //console.log('upload error', response);
                    self.upload_state = 'none';
                    self.upload_request = null;
                    self.upload_error = response;
                });
            },

            cancel: function()
            {
                if(this.upload_source){
                    console.log('upload cancel');
                    this.upload_source.cancel();
                }else{
                    console.log('Cannot cancel', this.upload_source);
                }
            },

            toggleAll(){
                this.selected = [];
                for(var i=0; i<this.items.length; ++i){
                    this.items[i].selected = this.selectAll;
                    if(this.selectAll){
                        this.selected.push(this.items[i].id);
                    }
                }
            },

            toggle(item){
                var idx = this.selected.indexOf(item.id);
                if(idx !== -1){
                    this.selected.splice(idx, 1);
                }else{
                    this.selected.push(item.id);
                }
                //item.selected = !item.selected;
            },

            hasSelection(){
                return (this.selected.length > 0);
            },

            destroy: function(item)
            {
                this.items = _.forEach(this.items, function(item) {
                    item.selected = false;
                });
                item.selected = true;
                this.deleteDialog.modal('show');
            },

            deleteMedias: function()
            {
                var item = _.find(this.items, function(item) {
                    return item.selected;
                });

                if(item){

                    var self = this;
                    this.delete_state = 'pending';
                    this.delete_error = null;

                    axios.delete(this.queriesBaseUrl + 'media/' + item.id).then(function(r){
                        self.items = _.filter(self.items, function(it) {
                            return it.id !== item.id;
                        });
                        self.deleteMedias();
                    }).catch(response => {
                        self.delete_state = 'none';
                        self.delete_error = response;
                    });
                }
                else{
                    this.selected = [];
                    this.delete_state = 'none';
                    this.deleteDialog.modal('hide');
                    this.refresh();
                }
            },

            download(item){
                window.location.href = this.queriesBaseUrl + 'media/' + item.id + '/download';
            }
        }
    }
</script>
