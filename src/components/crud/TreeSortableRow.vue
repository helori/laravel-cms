<style scoped>
    ul{
        display: block;
        margin: 0;
        padding: 0;
        list-style: none;
    }
    ul li{
        display: block;
        margin: 0;
        padding: 0;
    }
    .table{
        display: table;
        width: 100%;
        height: 100%;
    }
    .table-cell{
        display: table-cell;
        vertical-align: middle;
    }
    .tree-row{
        position: relative;
        padding: 3px 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        cursor: move;
    }
    .toggle{
        position: absolute;
        left: 0;
        top: 0;
        width: 39px;
        height: 100%;
        cursor: pointer;
        text-align: center;
        background: #F0F0F0;
    }
    .content{
        
    }
    .indent{
        position: relative;
        float: left;
        text-align: right;
        line-height: 40px;
    }
    .indented{
        position: relative;
        padding: 0 0 0 15px;
    }
    .fa-mail-reply{
        transform: rotate(180deg);
    }
</style>

<template>

    <div class="tree-group" 
        :class="'depth-' + depth" 
        :depth="depth">

        <template v-for="item in items">

            <div :id="item.id" 
                :parent-id="item.parent_id"
                :position="item.position"
                class="tree-row"
                draggable="true" 
                @dragstart="dragstart($event)" 
                @drag="drag($event)" 
                @dragend="dragend($event)"
                @dragenter="dragenter($event)"
                @dragover="dragover($event)"
                @dragleave="dragleave($event)"
                @drop="drop($event)">

                <div class="toggle" @click="toggleItem(item)" v-if="nested">
                    <div class="table">
                        <div class="table-cell">
                            <i class="fa" :class="{'fa-angle-right': !item.open, 'fa-angle-down': item.open}" v-if="item[childrenKey].length"></i>
                        </div>
                    </div>
                </div>

                <div class="content" :style="'margin-left: ' + depthOffset + 'px'">
                    
                    <div class="indent" :style="'width: ' + (depth * depthIndent) + 'px'" v-if="depth > 0">
                        <i class="fa fa-mail-reply"></i>
                    </div>

                    <div class="indented" :style="'margin-left:' + (depth * depthIndent) + 'px'">

                        <component
                            ref="rowComponent"
                            :is="rowComponent"
                            :item="item"
                            :base-uri="baseUri"
                            :uri="uri"
                            :options="options"
                            @update="update"
                            @destroy="destroy">
                        </component>

                    </div>
                </div>

            </div>

            <template v-if="nested">
                <tree-sortable-row
                    v-if="item[childrenKey].length > 0 && item.open"
                    :base-uri="baseUri"
                    :uri="uri"
                    :items-org="item[childrenKey]"
                    :depth="depth + 1"
                    :nested="nested"
                    :children-key="childrenKey"
                    :row-component="rowComponent"
                    :depth-offset="depthOffset"
                    :depth-indent="depthIndent"
                    @update="update"
                    @destroy="destroy"
                    @dragstarted="dragstart"
                    @dragged="drag"
                    @dragended="dragend"
                    @dragentered="dragenter"
                    @dragovered="dragover"
                    @dragleaved="dragleave"
                    @dropped="drop">
                </tree-sortable-row>
            </template>

        </template>
    </div>
    
</template>

<script>
    export default {
        data(){
            return {
                items: []
            }
        },

        props: {
            itemsOrg: {
                type: Array,
                required: true,
                default: function(){
                    return [];
                }
            },
            baseUri: {
                type: String,
                required: true
            },
            uri: {
                type: String,
                required: true
            },
            options: {
                type: Object,
                required: false,
                default: function(){
                    return {};
                }
            },
            depth: {
                type: Number,
                required: true
            },
            nested: {
                type: Boolean,
                default: false
            },
            childrenKey: {
                type: String,
                required: true
            },
            rowComponent: {
                required: true
            },
            depthOffset: {
                type: Number,
                default: 40
            },
            depthIndent: {
                type: Number,
                default: 30
            }
        },

        mounted(){
            this.setItems(this.itemsOrg);
        },

        watch: {
            itemsOrg: {
                handler: function (items) {
                    this.setItems(items);
                }
            }
        },

        methods: {
            setItems(items){
                if(this.nested){
                    var items = _.cloneDeep(items);
                    for(var i=0; i<items.length; ++i){
                        items[i].open = true;
                    }
                }
                this.items = items; //Object.assign({}, this.items, items);
            },

            toggleItem(item){
                item.open = !item.open;
            },

            update: function (item) {
                this.$emit('update', item);
            },

            destroy: function (item) {
                this.$emit('destroy', item);
            },

            // ------------------------------------
            //  D&D events
            // ------------------------------------
            dragstart(event){
                this.$emit('dragstarted', event);
            },

            drag(event){
                this.$emit('dragged', event);
            },

            dragend(event){
                this.$emit('dragended', event);
            },

            dragenter(event){
                this.$emit('dragentered', event);
            },

            dragover(event){
                this.$emit('dragovered', event);
            },

            dragleave(event){
                this.$emit('dragleaved', event);
            },

            drop(event){
                this.$emit('dropped', event);
            }
        }
    }
</script>
