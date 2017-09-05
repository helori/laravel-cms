<style scoped>
.media{
    margin: 10px 0 0 0;
}
.media{
    border: 4px solid #ddd;
    border-radius: 2px;
}
.media.active{
    border: 4px solid green;
}
.title{
    height: 30px;
    padding: 5px;
    font-size: 12px;
    text-align: center;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
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

    <div class="modal fade"tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                
                <div class="modal-header">
                    <button type="button " class="close" data-dismiss="modal" aria-hidden="true" tabindex="-1">&times;</button>
                    <h4 class="modal-title">
                        Select Media
                    </h4>
                </div>
                
                <div class="modal-body">

                    <div class="row narrow">
                        <div class="col col-sm-4">
                            <input-text
                                v-model="search.text"
                                name="search"
                                placeholder="Search..."
                                :autofocus="true">
                            </input-text>
                        </div>
                        <div class="col col-sm-8 text-right">
                            <pagination :pagination="pagination" @change="loadPage"></pagination>
                        </div>
                    </div>

                    <div class="row narrow">
                        <div class="col col-sm-3" v-for="media in pagination.data">
                            <div class="media" @click="toggle(media)" :class="{'active': isSelected(media.id)}">
                                <media-thumb
                                    :media="media">
                                </media-thumb>
                                <div class="title">{{ media.title }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-danger" v-if="readErrors">
                        {{ readErrors }}
                    </div>
                    
                </div>

                <!-- Modal Actions -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" tabindex="0">Cancel</button>

                    <button type="button" class="btn btn-primary" @click="submit">
                        Confirm selection
                    </button>
                </div>

            </div>
        </div>
    </div>

</template>

<script>

    import pagination from 'vue-crud/src/widgets/Pagination.vue'
    import inputText from 'vue-crud/src/inputs/InputText.vue'
    import mediaThumb from './MediaThumb.vue'

    export default {

        components: {
            pagination: pagination,
            mediaThumb: mediaThumb,
            inputText: inputText
        },

        data(){
            return{
                search: {
                    text: '',
                },
                pagination: {},
                page: 1,
                readStatus: null,
                readErrors: null,
                dialog: null,
                selected: []
            };
        },

        props: {
            uriPrefix: {
                type: String,
                required: false,
                default: ''
            },
            value: {
                type: Array,
                default(){
                    return [];
                }
            },
            multiple: {
                type: Boolean,
                required: false,
                default: false
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

            this.dialog = $(this.$el).on('shown.bs.modal', function (e) {
                $(this).find('input').first().focus();
            });

        },

        methods: {

            read: function()
            {
                this.readStatus = 'loading';

                var url = this.uriPrefix + '/api/media?limit=12&page=' + this.page;
                url += '&text=' + this.search.text;

                axios.get(url).then(response => {

                    this.readStatus = 'success';
                    this.pagination = response.data;

                }).catch(response => {

                    this.readStatus = 'error';
                    this.readErrors = response.response.data;

                });
            },

            loadPage(p){
                this.page = p;
                this.read();
            },

            open(){
                this.dialog.modal('show');
                this.page = 1;
                this.read();
            },

            close(){
                this.dialog.modal('hide');
            },

            submit(){
                this.$emit('input', this.selected);
            },

            toggle(media){

                var idx = _.findIndex(this.selected, function(m) { return m.id == media.id; });
                
                if(this.multiple){

                    if(idx === -1){
                        this.selected.push(media);
                    }else{
                        this.selected.splice(idx, 1);
                    }

                }else{

                    if(idx === -1){
                        this.selected = [media];
                    }else{
                        this.selected = [];
                    }

                }
            },

            isSelected(id){
                var idx = _.findIndex(this.selected, function(m) { return m.id == id; });
                return (idx !== -1);
            },
        }
    }
</script>
