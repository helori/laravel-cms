<style scoped>
    .document-uploader a,
    .document-uploader .file-input-wrapper{
        vertical-align: top;
    }
    .document-uploader img{
        width: 100px;
        margin-right: 5px;
    }
</style>

<template>
    <div class="document-uploader">
        
        <img :src="baseUrl + '/document-file?' + this.decache" 
            :alt="doc.title" 
            v-if="doc.mime && doc.mime.indexOf('image') !== -1">

        <div class="file-input-wrapper" v-if="!file || (!file.loaded && canAdd)" @click="trigger()">
            <label class="btn btn-primary">
                <i class="fa fa-folder-open"></i>
            </label>
            <input type="file"
                accept="image/jpeg,image/png,application/pdf"
                class="form-control file-input">
        </div>
        <div class="progress" v-if="file && file.loaded">
            <div class="progress-bar" 
                role="progressbar" 
                :style="'width: ' + (file ? 100 * file.loaded / file.size : 0) + '%'">
                    <span class="sr-only">{{ file ? 100 * file.loaded / file.size : 0 }}% Complete</span>
            </div>
        </div>
        <a :href="baseUrl + '/document-download/' + (doc ? doc.id : 0)"
            class="btn btn-default">
            <i class="fa fa-download"></i>
        </a>
    </div>

</template>

<script>
    export default {
        data() {
            return {
                doc: {},
                file: null,
                decache: Math.random().toString(36).substring(7)
                /*getUrl: this.baseUrl + '/documents-data',
                uploadUrl: this.baseUrl + '/document-upload',
                deleteUrl: this.baseUrl + '/document-delete',
                renameUrl: this.baseUrl + '/document-rename',
                expireUrl: this.baseUrl + '/document-expire',
                downloadUrl: this.baseUrl + '/document-download',*/
            };
        },
        props: [
            'baseUrl',
            'title'
        ],

        mounted() {
            //this.getItem();

            // -------------------------------------------------------
            //  Init browse button
            // -------------------------------------------------------
            var self = this;

            $(this.$el).on('change', '.file-input', function(e){
                self.file = e.target.files[0];
                self.upload();
                self.value = null;
            }).on('click', '.file-input', function(e){
                this.value = null;
            });
        },

        watch: {
            baseUrl: function(url){
                this.getItem();
                console.log('baseUrl', this.baseUrl);
            },
        },

        methods: {

            trigger() {
                $(this.$el).find('.file-input').trigger('click');
            },

            getItem() {
                this.$http.get(this.baseUrl + '/document-data').then(response => {
                    this.doc = response.data;
                    console.log('getItem', this.baseUrl, this.doc);
                });
            },

            // -------------------------------------------------------
            //  Upload
            // -------------------------------------------------------
            upload()
            {
                var formData = new FormData();
                formData.append('file', this.file, this.file.name);
                formData.append('docId', this.doc.id);

                this.$http.post(this.baseUrl + '/document-upload', formData, {
                    upload: {
                        onprogress: (e) => {
                            if (e.lengthComputable) {
                                this.file.loaded = e.loaded;
                                this.file.size = e.total;
                            }
                        }
                    }
                }).then(response => {
                    this.getItem();
                    this.decache = Math.random().toString(36).substring(7);
                    this.file = null;
                })
                .catch(response => {
                    console.log('upload error', response);
                });
            },

            destroy(id)
            {
                this.$http.delete(this.baseUrl + '/document-delete/' + id).then(response => {
                    this.getItem();
                })
                .catch(response => {
                    console.log('upload error', response);
                });
            }
        }
    }
</script>
