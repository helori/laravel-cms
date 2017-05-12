<style scoped>
    .medias-manager{
        position: absolute;
        top: 50px; bottom: 0;
        left: 0; right: 0;
        width: 100%;
    }
    .medias-manager .left{
        z-index: 1;
        position: absolute;
        top: 0; bottom: 0; left: 0;
        width: 500px; height: 100%;
        padding: 30px;
        overflow-x: hidden;
        overflow-y: scroll;
        box-shadow: 1px 0 4px rgba(0, 0, 0, 0.5);
    }
    .medias-manager .right{
        position: absolute;
        top: 0; bottom: 0; 
        left: 500px; right: 0;
        height: 100%;
        padding: 30px;
        overflow-x: hidden;
        overflow-y: scroll;
        background: #666;
    }

    .medias-manager .table{
        display: table;
    }
    .medias-manager .table > .cell{
        display: table-cell;
        width: 100%;
        padding: 20px 30px;
        vertical-align: middle;
    }
    .medias-manager input.file-input{
        position: absolute;
        left: -9999px;
    }
    .medias-manager input.file-input + label {
        cursor: pointer;
        background: rgba(0, 0, 0, 0.1);
        border: 3px dashed rgba(0, 0, 0, 0.3);
        text-align: center;
        font-style: italic;
        font-weight: 400;
        border-radius: 6px;
    }
    .medias-manager input.file-input + label:hover {
        background: rgba(0, 0, 0, 0.05);
    }
    .medias-manager h1{
        margin: 0 0 15px 0;
        font-size: 30px;
        font-weight: 400;
    }
    .medias-manager h2{
        margin: 0 0 15px 0;
        font-size: 16px;
        font-weight: 500;
    }
    .medias-manager .hint{
        margin: 0 0 15px 0;
        font-style: italic;
        color: rgba(0, 0, 0, 0.5);
    }
    .medias-manager p:last-child{
        margin: 0;
    }
    .medias-manager .item{
        position: relative;
        margin: 0 0 30px 0;
        padding: 5px;
        border-radius: 5px;
        box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
    }
    .medias-manager .item .selector{
        cursor: pointer;
        z-index: 5;
        position: absolute;
        top: 5px; left: 5px;
        width: 60px;
        height: 60px;
        line-height: 60px;
        font-size: 30px;
        text-align: center;
        border-bottom-right-radius: 5px;
        box-shadow: 2px 2px 3px rgba(0, 0, 0, 0.3);
    }
    .medias-manager .item .selector .inside{
        position: absolute;
        top: 0; left: 0;
        bottom: 5px; right: 5px;
        width: 55px;
        height: 55px;
        border-radius: 5px;
        box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3) inset;
        background: white;
    }
    .medias-manager .bg-grey{
        background: #DDD;
    }
</style>

<template>

    <div class="medias-manager">

        <div class="left">
            
            <h1>Vos fichiers médias <span v-if="items.length > 0">({{ items.length }})</span></h1>
            <div class="hint">Vous gérez ici les fichiers sur votre serveur : images, vidéos, documents... Vous pourrez ensuite les modifier, les associer aux éléments de votre site et les utiliser pour créer des liens dans vos textes.</div>

            <div v-if="upload_state != 'uploading'">
                <input type="file" multiple
                    accept="image/jpeg,image/png,application/pdf,video/mp4"
                    :id="'file-input-' + id" 
                    class="file-input">
                <label :for="'file-input-' + id" class="table filedrop">
                    <div class="cell">
                        <h2>Charger de nouveaux fichiers</h2>
                        <p>Vous pouvez glisser-déposer vos fichiers dans cette zone <br>(ou cliquer ici pour en sélectionner)</p>
                    </div>
                </label>
            </div>

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

            <div v-if="items.length > 0 && selected.length == 0" class="alert alert-info">
                <strong>Cliquez sur les vignettes pour sélectionner des fichiers</strong>
            </div>

            <button type="button" class="btn btn-danger btn-block" @click="openDeleteDialog()" v-if="selected.length > 0">
                <i class="fa fa-trash"></i> Supprimer les fichiers sélectionnés
            </button>

        </div>

        <div class="right">
            <div class="row">
                <div class="col-sm-4 col-md-3" v-for="item in items">

                    <div class="item"
                        :class="{'bg-success': isSelected(item.id), 'bg-grey': !isSelected(item.id)}">

                        <div class="selector" 
                            @click="toggleSelect(item.id)"
                            :class="{'bg-success': isSelected(item.id), 'bg-grey': !isSelected(item.id)}">
                            <div class="inside">
                                <i class="fa fa-check text-success" v-if="isSelected(item.id)"></i>
                            </div>
                        </div>

                        <media :media="item"
                            :assets-base-url="assetsBaseUrl"
                            :queries-base-url="queriesBaseUrl"
                            @deleted="refresh()">
                        </media>

                    </div>

                </div>
            </div>
        </div>

        <!-- Delete Dialog -->
        <div class="modal fade delete-dialog"tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Supprimer le fichier</h4>
                    </div>
                    
                    <div class="modal-body">
                        Les fichiers sélectionnés (il y en a {{ selected.length }}) sont sur le point d'être supprimés.
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
                id: 0,
                files: null,
                items: [],
                selected: [],

                upload_source: null,
                upload_state: 'none',
                upload_progress: 0,
                upload_total: 0,
                upload_error: null,

                delete_state: 'none',
                delete_error: null
            };
        },

        props: {
            'queries-base-url': {
                type: String,
                required: true,
                default: ''
            },
            'assets-base-url': { 
                type: String,
                required: true,
                default: ''
            }
        },

        mounted() {
            var self = this;
            this.id = Math.floor(Math.random()*(9999-1000+1)+1000);
            
            // -------------------------------------------------------
            //  Init browse button
            // -------------------------------------------------------
            $(this.$el).on('change', '.file-input', function(e){
                self.files = e.target.files;
                self.openUploadDialog();
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
                console.log('files dropped', e.originalEvent.dataTransfer.files);

                self.files = e.originalEvent.dataTransfer.files;
                self.openUploadDialog();
                self.upload();
            });
        },

        methods: {
            openUploadDialog: function ()
            {
                $(this.$el).find('#medias-upload-dialog').modal('show');
            },

            closeUploadDialog: function ()
            {
                $(this.$el).find('#medias-upload-dialog').modal('hide');
            },

            refresh: function()
            {
                return axios.get(this.queriesBaseUrl + 'medias').then(response => {
                    this.items = response.data;
                    console.log('refreshed !', this.items);
                }).catch(response => {
                    console.log('refresh error', response);
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

                axios.post(this.queriesBaseUrl + 'medias', formData, config).then(response => {
                    console.log('upload success', response);
                    self.upload_state = 'done';
                    self.upload_request = null;
                    for(var i=0; i<response.data.length; ++i){
                        this.items.push(response.data[i]);
                    }
                    this.closeUploadDialog();
                }).catch(response => {
                    console.log('upload error', response);
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

            toggleSelect: function(id){
                if(this.isSelected(id)){
                    this.unselect(id);
                }else{
                    this.select(id);
                }
            },

            select: function(id)
            {
                function onlyUnique(value, index, self) { 
                    return self.indexOf(value) === index;
                }
                this.selected.push(id);
                this.selected = this.selected.filter(onlyUnique);
            },

            unselect: function(id)
            {
                var idx = this.selected.indexOf(id);
                if(idx !== -1){
                    this.selected.splice(idx, 1);
                    console.log('unselected', id, this.selected);
                }
            },

            isSelected: function(id)
            {
                return (this.selected.indexOf(id) !== -1);
            },

            openDeleteDialog: function()
            {
                $(this.$el).find('.delete-dialog').modal('show');
            },

            closeDeleteDialog: function()
            {
                $(this.$el).find('.delete-dialog').modal('hide');
            },

            deleteMedias: function()
            {
                if(this.selected.length > 0){
                    
                    var self = this;
                    this.delete_state = 'pending';
                    this.delete_error = null;

                    axios.delete(this.queriesBaseUrl + 'medias/' + this.selected[0]).then(function(r){
                        self.unselect(self.selected[0]);
                        if(self.selected.length > 0){
                            self.deleteMedias();
                        }else{
                            self.delete_state = 'none';
                            self.closeDeleteDialog();
                            self.refresh();
                        }
                    }).catch(response => {
                        self.delete_state = 'none';
                        self.delete_error = response;
                        console.log('delete error', response);
                    });
                }
            }
        }
    }
</script>
