<style scoped>
h1{
    margin: 0 0 5px 0;
    font-size: 30px;
    font-weight: 300;
}
h3{
    margin: 30px 0 15px 0;
    font-size: 20px;
    font-weight: 300;
}
.subtitle{
    font-size: 16px;
    font-weight: 300;
    font-style: italic;
    margin: 0 0 15px 0;   
}
.col-left .btn-block, .col-left .alert{
    margin: 0 0 15px 0;
}
.search-bar, .btn-back{
    margin: 15px 0;
}    
.badge-danger{
    background: red;
}
.badge-danger a, .badge-danger a:hover{
    color: white;
    text-decoration: none;
}
.cell-thumb{
    width: 150px;
}
.title{
    font-weight: bold;
}
.menu{
    font-size: 12px;
}
.cell-pin{
    width: 30px;
}
.cell-id{
    width: 15px;
}
.pin{
    width: 20px;
    height: 20px;
    border-radius: 50%;
}
.pin.green{
    background: green;
}
.pin.red{
    background: red;
}
td .badge{
    margin-right: 4px;
}
</style>

<template>

    <div>

        <div class="row">
            <div class="col-md-3 col-left">
                
                <div v-if="updateItem === null">

                    <h1>Pages</h1>

                    <div class="subtitle">
                        <span v-if="readStatus === 'loading'">
                            Loading...
                        </span>
                        <span v-else-if="readStatus === 'success' && items.length === 0">
                            No result
                        </span>
                        <span v-else-if="readStatus === 'success'">
                            Pages loaded !
                        </span>
                        <span v-else-if="readStatus === 'error'">
                            Something wrong happened...
                        </span>
                    </div>

                    <div class="alert alert-danger" v-if="readErrors">
                        {{ readErrors }}
                    </div>

                    <button type="button" class="btn btn-primary btn-block" @click="openCreate">
                        <i class="fa fa-plus"></i> New page...
                    </button>


                    <h3>Menus :</h3>

                    <ul class="list-group">
                        <li v-for="menu in menus" 
                            class="list-group-item noselect" >

                            <span class="badge badge-danger" @click="$event.stopPropagation(); openMenuDestroy(menu)">
                                <i class="fa fa-trash"></i>
                            </span>

                            <span class="badge" @click="$event.stopPropagation(); openMenuUpdate(menu)">
                                <i class="fa fa-edit"></i>
                            </span>

                            <span class="badge">{{ menu.pages_count }}</span>
                            
                            {{ menu.title }}

                        </li>
                    </ul>

                    <button type="button" class="btn btn-primary btn-block" @click="openMenuCreate">
                        <i class="fa fa-plus"></i> New menu...
                    </button>

                </div>

                <div v-else>

                    <h1>Edit page</h1>

                    <div class="subtitle">

                    </div>

                    <button type="button" class="btn btn-default btn-block" @click="openUpdate(null)">
                        <i class="fa fa-arrow-left"></i> Back to list
                    </button>

                    <button type="button" 
                        class="btn btn-primary btn-block"
                        @click="update"
                        :disabled="updateStatus === 'loading'">
                        <i class="fa fa-spinner fa-spin" v-if="updateStatus === 'loading'"></i> Save modifications
                    </button>

                    <div class="alert alert-danger" v-if="updateErrors">
                        {{ updateErrors }}
                    </div>

                    <div class="alert alert-success text-center" v-if="updateStatus === 'success'">
                        <i class="fa fa-smile-o"></i> Modifications saved !
                    </div>

                    <h3>Belongs to menu :</h3>

                    <ul class="list-group">
                        <li v-for="menu in menus" 
                            class="list-group-item noselect" 
                            @click="setPageMenu(menu.id)">

                            <i class="fa" :class="{'fa-check-square-o': updateItem.menu_id == menu.id, 'fa-square-o': updateItem.menu_id != menu.id}"></i> {{ menu.title }}
                        </li>
                        <li class="list-group-item noselect" 
                            @click="setPageMenu(null)">

                            <i class="fa" :class="{'fa-check-square-o': !updateItem.menu_id, 'fa-square-o': updateItem.menu_id}"></i> (Aucun menu)
                        </li>
                    </ul>

                </div>

            </div>

            <div class="col-md-9">

                <panel v-if="updateItem === null">
                    <div slot="body">

                        <div class="table-responsive">
                            <table class="table table-condensed table-striped">
                                <tbody>
                                    <tr v-for="(item, idx) in items">
                                        <td class="cell-id">{{ item.id }}</td>
                                        <td class="cell-pin">
                                            <div class="pin" :class="{'green': item.published, 'red': !item.published}"></div>
                                        </td>
                                        <td class="cell-thumb">
                                            <media-thumb
                                                :media="item.image[0]">
                                            </media-thumb>
                                        </td>
                                        <td>
                                            <div class="title">{{ item.title }}</div>
                                            <div class="menu">
                                                <span class="badge" v-if="item.menu">{{ item.menu.title }}</span>
                                            </div>
                                        </td>
                                        <td class="text-right">

                                            <div class="btn-group">

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

                    </div>
                </panel>

                <panel v-else>
                    <div slot="body">

                        <page-form-update
                            ref="updatePageForm"
                            :uri-prefix="uriPrefix"
                            :item-org="updateItem"
                            :errors-org="updateErrors">
                        </page-form-update>

                    </div>
                </panel>

            </div>
        </div>

        <!-- Pages dialogs -->

        <dialog-edit
            ref="createPageDialog"
            title="Create a new page"
            :status="createStatus"
            @save="create">

            <div slot="body">
                <page-form-create
                    ref="createPageForm"
                    :item-org="createItem"
                    :errors-org="createErrors">
                </page-form-create>
            </div>

        </dialog-edit>

        <dialog-destroy
            ref="destroyPageDialog"
            title="Delete page"
            :message="destroyMessage"
            :status="destroyStatus"
            @destroy="destroy">
        </dialog-destroy>


        <!-- Menus dialogs -->

        <dialog-edit
            ref="createMenuDialog"
            title="Create a new menu"
            :status="createMenuStatus"
            @save="createMenu">

            <div slot="body">
                <menu-form
                    ref="createMenuForm"
                    :item-org="createMenuItem"
                    :errors-org="createMenuErrors"
                    :uri-prefix="uriPrefix">
                </menu-form>
            </div>

        </dialog-edit>

        <dialog-edit
            ref="updateMenuDialog"
            title="Update menu"
            :status="updateMenuStatus"
            @save="updateMenu">

            <div slot="body">
                <menu-form
                    ref="updateMenuForm"
                    :item-org="updateMenuItem"
                    :errors-org="updateMenuErrors"
                    :uri-prefix="uriPrefix">
                </menu-form>
            </div>

        </dialog-edit>

        <dialog-destroy
            ref="destroyMenuDialog"
            title="Delete menu"
            :message="destroyMenuMessage"
            :status="destroyMenuStatus"
            @destroy="destroyMenu">
        </dialog-destroy>

    </div>
</template>

<script>

    import panel from 'vue-crud/src/widgets/Panel.vue'
    import dialogEdit from 'vue-crud/src/widgets/DialogEdit.vue'
    import dialogDestroy from 'vue-crud/src/widgets/DialogDestroy.vue'

    import pageFormCreate from './PageFormCreate.vue'
    import pageFormUpdate from './PageFormUpdate.vue'
    import menuForm from './MenuForm.vue'

    import mediaThumb from '../medias/MediaThumb.vue'

    export default {

        components:{
            panel: panel,
            dialogEdit: dialogEdit,
            dialogDestroy: dialogDestroy,

            pageFormCreate: pageFormCreate,
            pageFormUpdate: pageFormUpdate,
            menuForm: menuForm,

            mediaThumb: mediaThumb
        },

        data(){
            return{
                items: [],
                menus: [],

                // ---
                
                readStatus: null,
                readErrors: null,

                createItem: {
                    title: null
                },
                createErrors: null,
                createStatus: null,

                updateItem: null,
                updateErrors: null,
                updateStatus: null,

                destroyItem: {},
                destroyErrors: null,
                destroyStatus: null,

                // ---

                readMenuStatus: null,
                readMenuErrors: null,

                createMenuItem: {
                    title: null
                },
                createMenuErrors: null,
                createMenuStatus: null,

                updateMenuItem: {},
                updateMenuErrors: null,
                updateMenuStatus: null,

                destroyMenuItem: {},
                destroyMenuErrors: null,
                destroyMenuStatus: null,
            };
        },

        props: {
            uriPrefix: {
                type: String,
                required: false,
                default: ''
            }
        },

        mounted() {
            this.read();
            this.readMenu();
        },

        computed: {
            destroyMessage(){
                return 'You are about to delete the page <strong>"' + this.destroyItem.title + '"</strong>.</p><p>This cannot be undone, so are you sure ?</p>';
            },
            destroyMenuMessage(){
                return 'You are about to delete the menu <strong>"' + this.destroyMenuItem.title + '"</strong>.</p><p>This cannot be undone, so are you sure ?</p>';
            }
        },

        methods: {

            read() {
                
                this.readStatus = 'loading';

                var url = this.uriPrefix + '/api/page';

                axios.get(url).then(response => {
                    
                    this.readStatus = 'success';
                    this.items = response.data;

                }).catch(response => {
                    
                    this.readStatus = 'error';
                    this.readErrors = response.response.data;

                });
            },

            openCreate(){
                this.createItem.title = null;
                this.createErrors = null;
                this.createStatus = null;
                this.$refs.createPageDialog.open();
            },

            create(){

                var item = this.$refs.createPageForm.item;
                this.createStatus = 'loading';
                
                axios.post(this.uriPrefix +'/api/page', item).then(response => {

                    this.createStatus = 'success';
                    this.$refs.createPageDialog.close();
                    this.read();

                }).catch(response => {
                    
                    this.createStatus = 'error';
                    this.createErrors = response.response.data;

                });
            },

            openUpdate(item){
                this.updateItem = item;
                this.updateErrors = null;
                this.updateStatus = null;
            },

            update(){

                var item = this.$refs.updatePageForm.item;
                this.updateStatus = 'loading';
                
                axios.put(this.uriPrefix +'/api/page/' + item.id, item).then(response => {

                    this.updateStatus = 'success';
                    this.read();

                }).catch(response => {
                    
                    this.updateStatus = 'error';
                    this.updateErrors = response.data;

                });
            },

            openDestroy(item){
                
                this.destroyItem = item;
                this.destroyErrors = null;
                this.destroyStatus = null;
                this.$refs.destroyPageDialog.open();
            },

            destroy() {

                this.destroyStatus = 'loading';
                
                axios.delete(this.uriPrefix + '/api/page/' + this.destroyItem.id).then(response => {

                    this.destroyStatus = 'success';
                    this.$refs.destroyPageDialog.close();
                    this.read();

                }).catch(response => {

                    this.destroyStatus = 'error';
                    this.destroyErrors = response.data;

                });

            },

            // ---

            readMenu(){

                this.readMenuStatus = 'loading';

                var url = this.uriPrefix + '/api/menu';
                
                var promise = axios.get(url);

                promise.then(response => {
                    
                    this.readMenuStatus = 'success';
                    this.menus = response.data;

                }).catch(response => {
                    
                    this.readMenuStatus = 'error';
                    this.readMenuErrors = response.response.data;

                });

                return promise;
            },

            openMenuCreate(){
                this.createMenuItem.title = null;
                this.createMenuErrors = null;
                this.createMenuStatus = null;
                this.$refs.createMenuDialog.open();
            },

            createMenu(){

                var item = this.$refs.createMenuForm.item;
                this.createMenuStatus = 'loading';
                
                axios.post(this.uriPrefix + '/api/menu', item).then(response => {

                    this.createMenuStatus = 'success';
                    this.$refs.createMenuDialog.close();
                    this.readMenu();

                }).catch(response => {
                    
                    this.createMenuStatus = 'error';
                    this.createMenuErrors = response.response.data;

                });

            },

            openMenuDestroy(item){
                
                this.destroyMenuItem = item;
                this.destroyMenuErrors = null;
                this.destroyMenuStatus = null;
                this.$refs.destroyMenuDialog.open();
            },

            destroyMenu() {

                this.destroyMenuStatus = 'loading';
                
                axios.delete(this.uriPrefix + '/api/menu/' + this.destroyMenuItem.id).then(response => {

                    this.destroyMenuStatus = 'success';
                    this.$refs.destroyMenuDialog.close();

                    this.readMenu();

                }).catch(response => {

                    this.destroyMenuStatus = 'error';
                    this.destroyMenuErrors = response.data;

                });

            },

            openMenuUpdate(item){
                
                this.updateMenuItem = item;
                this.updateMenuErrors = null;
                this.updateMenuStatus = null;
                this.$refs.updateMenuDialog.open();
            },

            updateMenu() {

                var item = this.$refs.updateMenuForm.item;
                this.updateMenuStatus = 'loading';
                
                axios.put(this.uriPrefix + '/api/menu/' + this.updateMenuItem.id, item).then(response => {

                    this.updateMenuStatus = 'success';
                    this.$refs.updateMenuDialog.close();

                    this.readMenu();

                }).catch(response => {

                    this.updateMenuStatus = 'error';
                    this.updateMenuErrors = response.data;

                });

            },

            setPageMenu(id){
                this.updateItem.menu_id = id;
            }
        }
    }
</script>
