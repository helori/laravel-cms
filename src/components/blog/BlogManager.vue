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
.date{
    font-size: 12px;
    color: #888;
}
.categories{
    font-size: 12px;
}
.cell-pin{
    width: 30px;
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

                    <h1>Blog manager</h1>

                    <div class="subtitle">
                        <span v-if="readStatus === 'loading'">
                            Loading...
                        </span>
                        <span v-else-if="readStatus === 'success' && pagination.total === 0">
                            No result
                        </span>
                        <span v-else-if="readStatus === 'success'">
                            {{ (pagination.current_page - 1) * pagination.per_page + 1 }}
                            à {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }} sur {{ pagination.total }}
                        </span>
                        <span v-else-if="readStatus === 'error'">
                            Something wrong happened...
                        </span>
                    </div>

                    <div class="alert alert-danger" v-if="readErrors">
                        {{ readErrors }}
                    </div>

                    <button type="button" class="btn btn-primary btn-block" @click="openCreate">
                        <i class="fa fa-plus"></i> New article...
                    </button>

                    <div class="search-bar">
                        <input-text
                            v-model="search.text"
                            name="search"
                            placeholder="Search articles..."
                            :autofocus="true">
                        </input-text>
                    </div>

                    <ul class="list-group">
                        <li v-for="category in categories" 
                            class="list-group-item noselect" 
                            @click="toggleCategory(category.id)">

                            <span class="badge badge-danger" @click="$event.stopPropagation(); openCategoryDestroy(category)">
                                <i class="fa fa-trash"></i>
                            </span>

                            <span class="badge" @click="$event.stopPropagation(); openCategoryUpdate(category)">
                                <i class="fa fa-edit"></i>
                            </span>

                            <span class="badge" v-if="category.articles">{{ category.articles.length }}</span>
                            
                            <i class="fa" :class="{'fa-check-square-o': hasCategory(category.id), 'fa-square-o': !hasCategory(category.id)}"></i> {{ category.title }}
                        </li>
                        <li class="list-group-item noselect" 
                            @click="toggleCategory(0)">
                            <i class="fa" :class="{'fa-check-square-o': hasCategory(0), 'fa-square-o': !hasCategory(0)}"></i> Autres
                        </li>
                    </ul>

                    <button type="button" class="btn btn-primary btn-block" @click="openCategoryCreate">
                        <i class="fa fa-plus"></i> New category...
                    </button>

                </div>

                <div v-else>

                    <h1>Edit article</h1>

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

                    <h3>Belongs to categories :</h3>

                    <ul class="list-group">
                        <li v-for="category in categories" 
                            class="list-group-item noselect" 
                            @click="toggleCategoryForArticle(category)">

                            <i class="fa" :class="{'fa-check-square-o': articleHasCategory(category), 'fa-square-o': !articleHasCategory(category)}"></i> {{ category.title }}
                        </li>
                    </ul>

                </div>

            </div>

            <div class="col-md-9">

                <panel v-if="updateItem === null">
                    <div slot="body">

                        <div class="text-right">
                            <pagination :pagination="pagination" @change="loadPage"></pagination>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-condensed table-striped">
                                <tbody>
                                    <tr v-for="(item, idx) in pagination.data">
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
                                            <div class="date">{{ item.published_at | date }}</div>
                                            <div class="categories">
                                                <span v-for="category in item.categories" class="badge">
                                                    {{ category.title }}
                                                </span>
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

                        <article-form-update
                            ref="updateForm"
                            :uri-prefix="uriPrefix"
                            :item-org="updateItem"
                            :errors-org="updateErrors"
                            :editor-css="editorCss"
                            @change="updateFormUpdated">
                        </article-form-update>

                    </div>
                </panel>

            </div>
        </div>

        <!-- Categories dialogs -->

        <dialog-edit
            ref="createCategoryDialog"
            title="Create a new category"
            :status="createCategoryStatus"
            @save="createCategory">

            <div slot="body">
                <category-form
                    ref="createCategoryForm"
                    :item-org="createCategoryItem"
                    :errors-org="createCategoryErrors"
                    @change="createCategoryFormUpdated">
                </category-form>
            </div>

        </dialog-edit>

        <dialog-edit
            ref="updateCategoryDialog"
            title="Update category"
            :status="updateCategoryStatus"
            @save="updateCategory">

            <div slot="body">
                <category-form
                    ref="updateCategoryForm"
                    :item-org="updateCategoryItem"
                    :errors-org="updateCategoryErrors"
                    @change="updateCategoryFormUpdated">
                </category-form>
            </div>

        </dialog-edit>

        <dialog-destroy
            ref="destroyCategoryDialog"
            title="Delete articategorycle"
            :message="destroyCategoryMessage"
            :status="destroyCategoryStatus"
            @destroy="destroyCategory">
        </dialog-destroy>

        <!-- Article dialogs -->

        <dialog-edit
            ref="createArticleDialog"
            title="Create a new article"
            :status="createStatus"
            @save="create">

            <div slot="body">
                <article-form-create
                    ref="createArticleForm"
                    :item-org="createItem"
                    :errors-org="createErrors"
                    @change="createFormUpdated">
                </article-form-create>
            </div>

        </dialog-edit>

        <dialog-destroy
            ref="destroyArticleDialog"
            title="Delete article"
            :message="destroyMessage"
            :status="destroyStatus"
            @destroy="destroy">
        </dialog-destroy>

    </div>
</template>

<script>

    require('vue-crud/src/filters/FilterDate.js')

    import inputText from 'vue-crud/src/inputs/InputText.vue'
    import pagination from 'vue-crud/src/widgets/Pagination.vue'
    import panel from 'vue-crud/src/widgets/Panel.vue'
    import dialogEdit from 'vue-crud/src/widgets/DialogEdit.vue'
    import dialogDestroy from 'vue-crud/src/widgets/DialogDestroy.vue'

    import articleFormCreate from './ArticleFormCreate.vue'
    import articleFormUpdate from './ArticleFormUpdate.vue'
    import categoryForm from './CategoryForm.vue'

    import mediaThumb from '../medias/MediaThumb.vue'

    export default {

        components:{
            inputText: inputText,
            pagination: pagination,
            panel: panel,
            dialogEdit: dialogEdit,
            dialogDestroy: dialogDestroy,

            articleFormCreate: articleFormCreate,
            articleFormUpdate: articleFormUpdate,
            categoryForm: categoryForm,

            mediaThumb: mediaThumb
        },

        data(){
            return{
                pagination: {},
                page: 1,
                search: {
                    text: '',
                    categories: []
                },

                categories: [],

                // ---

                readCategoryStatus: null,
                readCategoryErrors: null,

                createCategoryItem: {
                    title: null
                },
                createCategoryErrors: null,
                createCategoryStatus: null,

                updateCategoryItem: {},
                updateCategoryErrors: null,
                updateCategoryStatus: null,

                destroyCategoryItem: {},
                destroyCategoryErrors: null,
                destroyCategoryStatus: null,

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
            };
        },

        props: {
            uriPrefix: {
                type: String,
                required: false,
                default: ''
            },
            editorCss: {
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
            this.readCategories().then(response => {

                this.search.categories.push(0);
                for(var i=0; i<response.data.length; ++i){
                    this.search.categories.push(response.data[i].id);
                }

            });
        },

        computed: {
            destroyMessage(){
                return 'You are about to delete the article <strong>"' + this.destroyItem.title + '"</strong>.</p><p>This cannot be undone, so are you sure ?</p>';
            },
            destroyCategoryMessage(){
                return 'You are about to delete the category <strong>"' + this.destroyCategoryItem.title + '"</strong>. All articles belonging to this category will still exist. </p><p>Are you sure ?</p>';
            }
        },

        methods: {

            readCategories(){

                this.readCategoryStatus = 'loading';

                var url = this.uriPrefix + '/api/blog-category';
                
                var promise = axios.get(url);

                promise.then(response => {
                    
                    this.readCategoryStatus = 'success';
                    this.categories = response.data;

                }).catch(response => {
                    
                    this.readCategoryStatus = 'error';
                    this.readCategoryErrors = response.response.data;

                });

                return promise;
            },

            openCategoryCreate(){
                this.createCategoryItem.title = null;
                this.createCategoryErrors = null;
                this.createCategoryStatus = null;
                this.$refs.createCategoryDialog.open();
            },

            createCategory(){

                var item = this.$refs.createCategoryForm.item;
                this.createCategoryStatus = 'loading';
                
                axios.post(this.uriPrefix + '/api/blog-category', item).then(response => {

                    this.createCategoryStatus = 'success';
                    this.$refs.createCategoryDialog.close();
                    this.search.categories.push(response.data.id);
                    this.readCategories();

                }).catch(response => {
                    
                    this.createCategoryStatus = 'error';
                    this.createCategoryErrors = response.response.data;

                });

            },

            createCategoryFormUpdated(){
                this.createCategoryItem = this.$refs.createCategoryForm.item;
                this.createCategoryErrors = this.$refs.createCategoryForm.errors;
            },

            openCategoryDestroy(item){
                
                this.destroyCategoryItem = item;
                this.destroyCategoryErrors = null;
                this.destroyCategoryStatus = null;
                this.$refs.destroyCategoryDialog.open();
            },

            destroyCategory() {

                this.destroyCategoryStatus = 'loading';
                
                axios.delete(this.uriPrefix + '/api/blog-category/' + this.destroyCategoryItem.id).then(response => {

                    this.destroyCategoryStatus = 'success';
                    this.$refs.destroyCategoryDialog.close();

                    var idx = this.search.categories.indexOf(this.destroyCategoryItem.id);
                    if(idx !== -1){
                        this.search.categories.splice(idx, 1);
                    }

                    this.readCategories();

                }).catch(response => {

                    this.destroyCategoryStatus = 'error';
                    this.destroyCategoryErrors = response.data;

                });

            },

            openCategoryUpdate(item){
                
                this.updateCategoryItem = item;
                this.updateCategoryErrors = null;
                this.updateCategoryStatus = null;
                this.$refs.updateCategoryDialog.open();
            },

            updateCategory() {

                var item = this.$refs.updateCategoryForm.item;
                this.updateCategoryStatus = 'loading';
                
                axios.put(this.uriPrefix + '/api/blog-category/' + this.updateCategoryItem.id, item).then(response => {

                    this.updateCategoryStatus = 'success';
                    this.$refs.updateCategoryDialog.close();

                    this.readCategories();

                }).catch(response => {

                    this.updateCategoryStatus = 'error';
                    this.updateCategoryErrors = response.data;

                });

            },

            updateCategoryFormUpdated(){
                this.updateCategoryItem = this.$refs.updateCategoryForm.item;
                this.updateCategoryErrors = this.$refs.updateCategoryForm.errors;
            },

            read() {
                
                this.readStatus = 'loading';

                var url = this.uriPrefix + '/api/blog-article?page=' + this.page;
                url += '&text=' + this.search.text;
                url += '&categories=' + this.search.categories;
                
                axios.get(url).then(response => {
                    
                    this.readStatus = 'success';
                    this.pagination = response.data;

                }).catch(response => {
                    
                    this.readStatus = 'error';
                    this.readErrors = response.response.data;

                });
            },

            openCreate(){
                this.createItem.title = null;
                this.createErrors = null;
                this.createStatus = null;
                this.$refs.createArticleDialog.open();
            },

            create(){

                var item = this.$refs.createArticleForm.item;
                this.createStatus = 'loading';
                
                axios.post(this.uriPrefix +'/api/blog-article', item).then(response => {

                    this.createStatus = 'success';
                    this.$refs.createArticleDialog.close();
                    this.read();

                }).catch(response => {
                    
                    this.createStatus = 'error';
                    this.createErrors = response.response.data;

                });
            },

            createFormUpdated(){
                this.createItem = this.$refs.createForm.item;
                this.createErrors = this.$refs.createForm.errors;
            },

            openUpdate(item){
                this.updateItem = item;
                this.updateErrors = null;
                this.updateStatus = null;
            },

            update(){

                var item = this.$refs.updateForm.item;
                this.updateStatus = 'loading';
                
                axios.put(this.uriPrefix +'/api/blog-article/' + item.id, item).then(response => {

                    this.updateStatus = 'success';
                    this.read();

                }).catch(response => {
                    
                    this.updateStatus = 'error';
                    this.updateErrors = response.data;

                });
            },

            updateFormUpdated(){
                this.updateItem = this.$refs.updateForm.item;
                this.updateErrors = this.$refs.updateForm.errors;
            },

            openDestroy(item){
                
                this.destroyItem = item;
                this.destroyErrors = null;
                this.destroyStatus = null;
                this.$refs.destroyArticleDialog.open();
            },

            destroy() {

                this.destroyStatus = 'loading';
                
                axios.delete(this.uriPrefix + '/api/blog-article/' + this.destroyItem.id).then(response => {

                    this.destroyStatus = 'success';
                    this.$refs.destroyArticleDialog.close();
                    this.read();

                }).catch(response => {

                    this.destroyStatus = 'error';
                    this.destroyErrors = response.data;

                });

            },

            loadPage(p){
                this.page = p;
                this.read();
            },

            hasCategory(id){
                return (this.search.categories.indexOf(id) !== -1);
            },

            toggleCategory(id){
                var idx = this.search.categories.indexOf(id);
                if(idx !== -1){
                    this.search.categories.splice(idx, 1);
                }else{
                    this.search.categories.push(id);
                }
            },

            articleHasCategory(category){
                var idx = _.findIndex(this.updateItem.categories, function(cat) { return cat.id == category.id; });
                return (idx !== -1);
            },

            toggleCategoryForArticle(category){
                var idx = _.findIndex(this.updateItem.categories, function(cat) { return cat.id == category.id; });
                if(idx === -1){
                    this.updateItem.categories.push(category);
                }else{
                    this.updateItem.categories.splice(idx, 1);
                }
            }
        }
    }
</script>
