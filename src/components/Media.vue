<style scoped>
    .modal-dialog{
        width: 1000px;
        max-width: 95%;
    }
    input.file-input{
        position: absolute;
        left: -9999px;
    }
    input.file-input + label {
        position: relative;
        display: inline-block;
        cursor: pointer;
    }
    .row.narrow{
        margin-left: -2.5px;
        margin-right: -2.5px;
    }
    .row.narrow .col{
        padding-left: 2.5px;
        padding-right: 2.5px;
    }
    .item{
        
    }
    .bottom{
        padding: 5px;
    }
    .infos{
        margin: 5px 0 0 0;
        text-align: center;
        line-height: 20px;
    }
    .item .infos{
        min-height: 40px;
    }
    .mime{
        color: black;
        font-weight: bold;
    }
    .size{
        font-weight: bold;
    }
    .dim{
        font-weight: bold;
    }
    .path{
        color: #888;
        font-size: 12px;
        font-style: italic;
    }
    .preview{
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
    .modal-dialog .preview{
        padding-bottom: 80%;
    }
    .preview .image{
        z-index: 1;
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background-position: center center;
        background-repeat: no-repeat;
        background-size: contain;
    }
    .preview video, 
    .preview iframe{
        z-index: 1;
        position: absolute;
        width: 100%; height: 100%;
        top: 0; bottom: 0;
        left: 0; right: 0;
        background: black;
        border: 0;
        box-shadow: none;
    }
    .preview .text-wrapper{
        display: table;
        z-index: 2;
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: #666;
    }
    .preview .text-wrapper .text{
        display: table-cell;
        vertical-align: middle;
        text-align: center;
        color: white;
        font-size: 12px;
    }
    .preview .display{
        position: absolute;
        z-index: 2;
    }
    .preview .cropper{
        position: absolute;
        z-index: 3;
        cursor: move;
    }
    p:last-child{
        margin: 0;
    }
    .grab{
        z-index: 4;
        position: absolute;
        width: 20px; height: 20px;
        border: yellow 4px solid;
    }
    .grab.top-left{
        top: 0; left: 0;
        border-right: 0;
        border-bottom: 0;
        cursor: nwse-resize;
    }
    .grab.top-right{
        top: 0; right: 0;
        border-left: 0;
        border-bottom: 0;
        cursor: nesw-resize;
    }
    .grab.bottom-left{
        bottom: 0; left: 0;
        border-right: 0;
        border-top: 0;
        cursor: nesw-resize;
    }
    .grab.bottom-right{
        bottom: 0; right: 0;
        border-left: 0;
        border-top: 0;
        cursor: nwse-resize;
    }
    .grab.top{
        left: 50%; top: 0; margin-left: -20px/2;
        border-left: 0;
        border-right: 0;
        border-bottom: 0;
        cursor: ns-resize;
    }
    .grab.bottom{
        left: 50%; bottom: 0; margin-left: -20px/2;
        border-left: 0;
        border-right: 0;
        border-top: 0;
        cursor: ns-resize;
    }
    .grab.left{
        left: 0; top: 50%; margin-top: -20px/2;
        border-right: 0;
        border-top: 0;
        border-bottom: 0;
        cursor: ew-resize;
    }
    .grab.right{
        right: 0; top: 50%; margin-top: -20px/2;
        border-left: 0;
        border-top: 0;
        border-bottom: 0;
        cursor: ew-resize;
    }
    .mask{
        z-index: 3;
        position: absolute;
        background: rgba(0, 0, 0, 0.5);
    }
    .help-block{
        margin-bottom: 0;
        font-style: italic;
        font-size: 12px;
    }
</style>

<template>

    <div>
        
        <div class="item" v-if="item">
            <div class="preview">
                <div class="image"
                    v-if="item && item.mime && item.mime.indexOf('image') !== -1"
                    :style="'background-image:url(' + assetsBaseUrl + item.filepath + '?' + decache + ')'">
                </div>
                <video controls v-else-if="item && item.mime && item.mime.indexOf('video') !== -1">
                    <source :src="assetsBaseUrl + item.filepath + '?' + decache" :type="item.mime" />
                </video>
                <div class="text-wrapper" v-else>
                    <div class="text">
                        <div>{{ item.mime }}</div>
                    </div>
                </div>
            </div>
            <div class="bottom">
                <div class="actions">
                    <div class="row narrow">
                        <div class="col col-xs-4">
                            <button type="button" 
                                class="btn btn-default btn-block" 
                                @click="openUpdateDialog(item);">
                                <i class="fa fa-edit"></i>
                            </button>
                        </div>
                        <div class="col col-xs-4">
                            <a :href="'/' + item.filepath" 
                                :download="item.filename"
                                class="btn btn-default btn-block">
                                <i class="fa fa-download"></i>
                            </a>
                        </div>
                        <div class="col col-xs-4">
                            <button type="button" 
                                class="btn btn-danger btn-block" 
                                title="Supprimer le fichier"
                                @click="deleteMedia(item);">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="infos">
                    <div><span class="mime">{{item.mime}}</span> | <span class="size text-info">{{ (item.size / 1000) }} ko</span></div>
                    <div v-if="item && item.mime && item.mime.indexOf('image') !== -1" class="dim text-success">{{item.width}} x {{item.height}} px</div>
                </div>
            </div>
        </div>

        <!-- Update Dialog -->
        <div class="modal fade media-update-dialog"tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    
                    <div class="modal-header">
                        <button type="button " class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Modifier le fichier</h4>
                    </div>
                    
                    <div class="modal-body">
                        
                        <div v-if="item">

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="infos">
                                        <div><span class="mime">{{item.mime}}</span> | <span class="size text-info">{{item.size / 1000}} ko</span></div>
                                        <div v-if="item && item.mime && item.mime.indexOf('image') !== -1" class="dim text-success">{{item.width}} x {{item.height}} px</div>
                                        <div class="path">{{item.filepath}}</div>
                                    </div>

                                    <hr>

                                    <form class="form-horizontal">
                                        <div class="form-group">
                                            <label :for="'title-' + id" class="col-sm-4 control-label">Nom du fichier :</label>
                                            <div class="col-sm-8">
                                                <input type="text" 
                                                    class="form-control" 
                                                    :id="'title-' + id" 
                                                    placeholder="" 
                                                    v-model="item.title"
                                                    @change="setState('modified')">
                                            </div>
                                        </div>

                                        <div v-if="item.type == 'image'">

                                            <div class="form-group">
                                                <label :for="'force_size_type-' + id" class="col-sm-4 control-label">Taille finale :</label>
                                                <div class="col-sm-8">

                                                    <select class="form-control"
                                                        :id="'force_size_type-' + id"
                                                        v-model="force_size.type"
                                                        @change="setState('modified'); updateForceSize()">

                                                        <option value="none">{{ size_final.w }} x {{ size_final.h }} px</option>
                                                        <option value="width">Forcer la largeur</option>
                                                        <option value="height">Forcer la hauteur</option>

                                                    </select>

                                                    <div class="help-block" v-if="force_size.type == 'width'">
                                                        La hauteur sera adaptée automatiquement
                                                    </div>
                                                    <div class="help-block" v-if="force_size.type == 'height'">
                                                        La largeur sera adaptée automatiquement
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group" v-if="force_size.type != 'none'">
                                                <label :for="'force_size_value-' + id" class="col-sm-4 control-label">
                                                    <span v-if="force_size.type == 'width'">Largeur :</span>
                                                    <span v-if="force_size.type == 'height'">Hauteur :</span>
                                                </label>
                                                <div class="col-sm-8">
                                                    <input type="number" 
                                                        class="form-control" 
                                                        :id="'force_size_value-' + id"
                                                        placeholder="" 
                                                        v-model="force_size.value"
                                                        @change="setState('modified')">
                                                    <div class="help-block" v-if="force_size.type == 'width'">
                                                        La hauteur sera de {{ Math.round(force_size.value * size_cropper.h / size_cropper.w) }}px
                                                    </div>
                                                    <div class="help-block" v-if="force_size.type == 'height'">
                                                        La largeur sera de {{ Math.round(force_size.value * size_cropper.w / size_cropper.h) }}px
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="alert alert-warning" v-if="force_size.type == 'width' && force_size.value > size_final.w">
                                                Vous risquez de pixeliser votre image en forçant une largeur supérieure à {{ size_final.w }}px
                                            </div>
                                            <div class="alert alert-warning" v-if="force_size.type == 'height' && force_size.value > size_final.h">
                                                Vous risquez de pixeliser votre image en forçant une hauteur supérieure à {{ size_final.h }}px
                                            </div>

                                            <div class="form-group">
                                                <label :for="'compression_active-' + id" class="col-sm-4 control-label">Compression :</label>
                                                <div class="col-sm-8">
                                                    <div class="checkbox">
                                                        <label :for="'compression_active-' + id">
                                                            <input type="checkbox"
                                                                :id="'compression_active-' + id"
                                                                v-model="compression.active"
                                                                @change="setState('modified')">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" v-if="compression.active">
                                                <label :for="'compression_quality-' + id" class="col-sm-4 control-label">Qualité :</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control"
                                                        :id="'compression_quality-' + id"
                                                        v-model="compression.quality"
                                                        @change="setState('modified')">
                                                        <option value="100">Sans perte</option>
                                                        <option value="95">95%</option>
                                                        <option value="90">90%</option>
                                                        <option value="85">85%</option>
                                                        <option value="80">80%</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div v-if="update_error" class="alert alert-danger">
                                            <div class="text-center">
                                                <p><strong>{{ update_error.title }}</strong></p>
                                                <p>{{ update_error.message }}</p>
                                            </div>
                                        </div>

                                        <div v-if="update_state == 'done'" class="alert alert-success">
                                            <div class="text-center">
                                                <p><strong>C'est tout bon !</strong></p>
                                            </div>
                                        </div>

                                    </form>

                                </div>

                                <div class="col-md-6">

                                    <div v-if="item.type === 'image'">

                                        <div id="crop-wrapper" class="preview">
                                            <div class="image" :style="'background-image:url(' + assetsBaseUrl + item.filepath + '?' + decache + ')'"></div>
                                            <div class="display" :style="'left:' + size_display.x + 'px;top:' + size_display.y + 'px;width:' + size_display.w + 'px;height:' + size_display.h + 'px;'">
                                                <div class="cropper" 
                                                    :style="'left:' + size_cropper.x + 'px;top:' + size_cropper.y + 'px;width:' + size_cropper.w + 'px;height:' + size_cropper.h + 'px;'">

                                                    <div class="grab top-left" type="top-left"></div>
                                                    <div class="grab top-right" type="top-right"></div>
                                                    <div class="grab bottom-left" type="bottom-left"></div>
                                                    <div class="grab bottom-right" type="bottom-right"></div>
                                                    <div class="grab top" type="top"></div>
                                                    <div class="grab bottom" type="bottom"></div>
                                                    <div class="grab left" type="left"></div>
                                                    <div class="grab right" type="right"></div>
                                                </div>
                                            </div>
                                            <div class="mask mask-left" :style="'left:0;top:0;height:100%;width:' + (size_display.x + size_cropper.x) + 'px'"></div>
                                            <div class="mask mask-right" :style="'right:0;top:0;height:100%;width:' + (size_viewport.w - size_display.x - size_cropper.x - size_cropper.w) + 'px'"></div>
                                            <div class="mask mask-top" :style="'top:0;left:' + (size_display.x + size_cropper.x) + 'px;height:' + (size_display.y + size_cropper.y) + 'px;width:' + size_cropper.w + 'px'"></div>
                                            <div class="mask mask-bottom" :style="'bottom:0;left:' + (size_display.x + size_cropper.x) + 'px;height:' + (size_viewport.h - size_display.y - size_cropper.y - size_cropper.h) + 'px;width:' + size_cropper.w + 'px'"></div>
                                        </div>

                                        <div class="infos">
                                            <div class="dim">{{ size_final.w }} x {{ size_final.h }} px</div>
                                        </div>

                                    </div>

                                    <div v-else-if="item.type === 'pdf'">
                                        <div class="preview">
                                            <iframe :src="assetsBaseUrl + item.filepath + '?' + decache"></iframe>
                                        </div>
                                    </div>

                                    <div v-else-if="item.type === 'video'">
                                        <div class="preview">
                                            <video controls>
                                                <source :src="assetsBaseUrl + item.filepath + '?' + decache" :type="item.mime" />
                                            </video>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <div class="text-center">
                            <button type="button" 
                                class="btn btn-primary" 
                                v-if="update_state == 'modified' || update_state == 'pending'"
                                @click="updateMedia()"
                                :disabled="update_state == 'pending'">

                                <span v-if="update_state == 'modified'">
                                    Appliquer les modifications
                                </span>
                                <span v-if="update_state == 'pending'">
                                    <i class="fa fa-spinner fa-spin"></i> Modifications en cours...
                                </span>

                            </button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">
                                <i class="fa fa-close"></i> Fermer
                            </button>
                        </div>
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
                item: null,
                decache: '',

                size_viewport: {w: 0, h: 0},
                size_original: {w: 0, h: 0},
                size_display: {x: 0, y: 0, w: 0, h: 0},
                size_cropper: {x: 0, y: 0, w: 0, h: 0},
                size_final: {x: 0, y: 0, w: 0, h: 0},

                drag_type: null,
                drag_from: {
                    x: 0, y: 0,
                    cropper: {x: 0, y: 0, w: 0, h: 0}
                },

                force_size: {
                    type: 'none',
                    value: 0
                },
                compression: {
                    active: false,
                    quality: 100
                },

                update_state: 'none',
                update_error: null
            };
        },

        props: {
            'media': null,
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

        mounted()
        {
            var self = this;

            this.item = this.media;

            this.id = Math.floor(Math.random()*(9999-1000+1)+1000);
            this.decache = new Date().getTime();

            $(this.$el).find('.media-update-dialog').on('shown.bs.modal', function () {
                console.log('prepare');
                self.prepareEditor();
            });

            // -------------------------------------------------------
            //  Images cropper
            // -------------------------------------------------------
            this.initCropperMouse();
        },

        watch: {
            media: function(media){
                this.item = media;
            }
        },

        filters: {

        },

        methods: {
            openUpdateDialog: function (item)
            {
                this.item = item;
                $(this.$el).find('.media-update-dialog').modal('show');
            },

            closeUpdateDialog: function ()
            {
                $(this.$el).find('.media-update-dialog').modal('hide');
            },

            deleteMedia: function(item)
            {
                axios.delete(this.queriesBaseUrl + 'medias/' + item.id).then(response => {
                    this.$emit('deleted', item.id);
                }).catch(response => {
                    console.log('delete error', response);
                });
            },

            prepareEditor: function()
            {
                var self = this;
                self.initCropper();

                var image = new Image();
                image.onload = function()
                {
                    console.log('image loaded');

                    self.size_original.w = this.width;
                    self.size_original.h = this.height;
                    var prop_image = self.size_original.w / self.size_original.h;

                    var viewport = $(self.$el).find('#crop-wrapper .image');
                    self.size_viewport.w = viewport.width();
                    self.size_viewport.h = Math.floor(viewport.height());
                    var prop_viewport = self.size_viewport.w / self.size_viewport.h;
                    
                    if(prop_image > prop_viewport){
                        self.size_display.x = 0;
                        self.size_display.w = self.size_viewport.w;
                        self.size_display.h = Math.round(self.size_viewport.w / prop_image);
                        self.size_display.y = Math.round((self.size_viewport.h - self.size_display.h) / 2);
                    }else{
                        self.size_display.y = 0;
                        self.size_display.h = self.size_viewport.h;
                        self.size_display.w = Math.round(self.size_viewport.h * prop_image);
                        self.size_display.x = Math.round((self.size_viewport.w - self.size_display.w) / 2);
                    }
                    self.size_cropper.x = 0;
                    self.size_cropper.y = 0;
                    self.size_cropper.w = self.size_display.w;
                    self.size_cropper.h = self.size_display.h;

                    self.updateFinalSize();

                    console.log('viewport', self.size_viewport.w, self.size_viewport.h);
                    console.log('display', self.size_display.x, self.size_display.y, self.size_display.w, self.size_display.h);
                    console.log('cropper', self.size_cropper.x, self.size_cropper.y, self.size_cropper.w, self.size_cropper.h);
                    console.log('final', self.size_final.x, self.size_final.y, self.size_final.w, self.size_final.h);
                };
                image.src = this.assetsBaseUrl + this.item.filepath + '?' + this.decache;
            },

            setState: function(state){
                this.update_state = state;
            },

            updateMedia: function()
            {
                this.setState('pending');
                this.update_error = null;

                var data = {
                    title: this.item.title,
                    rect: this.size_final,
                    force: this.force_size.type,
                    force_value: this.force_size.value,
                    compression: this.compression.active,
                    quality: this.compression.quality
                };

                axios.put(this.queriesBaseUrl + 'medias/' + this.item.id, data).then(response => {
                    console.log('update success', response);
                    this.setState('done');
                    this.item = response.data;
                    this.decache = new Date().getTime();
                    this.prepareEditor();
                }).catch(response => {
                    this.setState('modified');
                    this.update_error = response.response.data;
                    if(response.response.data.media){
                        this.item = response.response.data.media;
                        this.decache = new Date().getTime();
                        this.prepareEditor();
                    }
                });
            },

            updateForceSize: function()
            {
                if(this.force_size.type == 'none'){
                    this.force_size.value = 0;
                }else if(this.force_size.type == 'width'){
                    this.force_size.value = this.size_final.w;
                }else if(this.force_size.type == 'height'){
                    this.force_size.value = this.size_final.h;
                }
            },

            updateFinalSize: function()
            {
                var scale = this.size_original.w / this.size_display.w;

                this.size_final.x = Math.round(this.size_cropper.x * scale);
                this.size_final.y = Math.round(this.size_cropper.y * scale);
                this.size_final.w = Math.round(this.size_cropper.w * scale);
                this.size_final.h = Math.round(this.size_cropper.h * scale);
            },

            // ----------------------------------------------------------------
            //  Crop
            // ----------------------------------------------------------------
            initCropperMouse: function()
            {
                var self = this;

                $(document).mouseup(function()
                {
                    self.drag_type = null;
                });

                $(document).mousemove(function(e)
                {
                    e.stopPropagation();
                    
                    if(self.drag_type == 'top')                 {self.dragTop(e);}
                    else if(self.drag_type == 'bottom')         {self.dragBottom(e);}
                    else if(self.drag_type == 'left')           {self.dragLeft(e);}
                    else if(self.drag_type == 'right')          {self.dragRight(e);}
                    else if(self.drag_type == 'top-left')       {self.dragTop(e); self.dragLeft(e);}
                    else if(self.drag_type == 'top-right')      {self.dragTop(e); self.dragRight(e);}
                    else if(self.drag_type == 'bottom-left')    {self.dragBottom(e); self.dragLeft(e);}
                    else if(self.drag_type == 'bottom-right')   {self.dragBottom(e); self.dragRight(e);}
                    else if(self.drag_type == 'move')           {self.dragMove(e);}
                    
                    if(self.drag_type !== null)
                    {
                        self.updateFinalSize();
                        self.updateForceSize();
                        self.setState('modified');
                    }
                });
            },

            // ----------------------------------------------------------------
            //  Crop with mouse
            // ----------------------------------------------------------------
            initCropper: function()
            {
                var self = this;
                var cropper = $(this.$el).find('.cropper');

                cropper.mousedown(function(e){
                    self.drag_type = 'move';
                    self.initDrag(e);
                });

                cropper.find('.grab').mousedown(function(e){
                    e.stopPropagation();
                    self.drag_type = $(this).attr('type');
                    self.initDrag(e);
                });
            },

            initDrag: function(e)
            {
                this.drag_from.x = e.pageX;
                this.drag_from.y = e.pageY;

                this.drag_from.cropper.x = this.size_cropper.x;
                this.drag_from.cropper.y = this.size_cropper.y;
                this.drag_from.cropper.w = this.size_cropper.w;
                this.drag_from.cropper.h = this.size_cropper.h;
            },

            dragMove: function(e)
            {
                var dx = e.pageX - this.drag_from.x;
                var dy = e.pageY - this.drag_from.y;

                dx = Math.max(-this.drag_from.cropper.x, dx);
                dx = Math.min(this.size_display.w - this.drag_from.cropper.x - this.drag_from.cropper.w, dx);

                dy = Math.max(-this.drag_from.cropper.y, dy);
                dy = Math.min(this.size_display.h - this.drag_from.cropper.y - this.drag_from.cropper.h, dy);

                this.size_cropper.x = this.drag_from.cropper.x + dx;
                this.size_cropper.y = this.drag_from.cropper.y + dy;
            },

            dragTop: function(e)
            {
                var display = $(this.$el).find('#crop-wrapper .display');
                var display_y = display.offset().top;

                var dy = Math.round(e.pageY - display_y);
                
                dy = Math.max(dy, 0);
                dy = Math.min(dy, this.size_cropper.y + this.size_cropper.h);
                
                this.size_cropper.h = this.size_cropper.y + this.size_cropper.h - dy;
                this.size_cropper.y = dy;
            },

            dragBottom: function(e)
            {
                var display = $(this.$el).find('#crop-wrapper .display');
                var display_y = display.offset().top;

                var h = Math.round(e.pageY - display_y - this.size_cropper.y);
                
                h = Math.max(h, 0);
                h = Math.min(h, this.size_display.h - this.size_cropper.y);
                
                this.size_cropper.h = h;
            },

            dragLeft: function(e)
            {
                var display = $(this.$el).find('#crop-wrapper .display');
                var display_x = display.offset().left;

                var dx = Math.round(e.pageX - display_x);
                
                dx = Math.max(dx, 0);
                dx = Math.min(dx, this.size_cropper.x + this.size_cropper.w);
                
                this.size_cropper.w = this.size_cropper.x + this.size_cropper.w - dx;
                this.size_cropper.x = dx;
            },

            dragRight: function(e)
            {
                var display = $(this.$el).find('#crop-wrapper .display');
                var display_x = display.offset().left;

                var w = Math.round(e.pageX - display_x - this.size_cropper.x);
                
                w = Math.max(w, 0);
                w = Math.min(w, this.size_display.w - this.size_cropper.x);
                
                this.size_cropper.w = w;
            }
        }
    }
</script>
