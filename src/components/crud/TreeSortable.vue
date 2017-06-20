<style scoped>
    .tree-sortable{
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }
    .placeholder{
        background: yellow;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        line-height: 40px;
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
    <div class="tree-sortable"
        @dragover="dragmove($event)">

        <!-- parentId: {{ dragParentId }}, position: {{ dragPosition }} -->

        <tree-sortable-row
            :base-uri="baseUri"
            :uri="uri"
            :items-org="items"
            :options="options"
            :depth="0"
            :nested="options['nested']"
            :children-key="options['children-key']"
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

        <div class="placeholder" 
            :style="'padding-left: ' + depthOffset + 'px'"
            v-show="dragging">
            
            <div class="indent" :style="'width: ' + (dragDepth * depthIndent) + 'px'" v-if="dragDepth > 0">
                <i class="fa fa-mail-reply"></i>
            </div>

            <div class="indented" :style="'margin-left:' + (dragDepth * depthIndent) + 'px'">
                Mettre ici !
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        data() {
            return {
                items: [],

                dragging: false,
                placeholder: null,

                sourceRow: null,
                sourceId: null,

                targetRow: null,
                targetId: null,
                targetDepth: 0,

                dragX: 0,
                dragY: 0,
                dragBefore: null,
                dragDepth: 0,
                dragParentId: 0,
                dragPosition: 0,

                // CSS values
                depthOffset: 40,
                depthIndent: 30
            };
        },

        props: {
            itemsOrg: {
                required: true
            },
            baseUri: {
                type: String,
                required: true
            },
            uri: {
                type: String,
                required: true
            },
            rowComponent: {
                required: true
            },
            options: {
                required: false
            }
        },

        mounted() {
            //this.getItems();
            this.placeholder = $(this.$el).find('.placeholder');
            if(!this.options.nested){
                this.depthOffset = 0;
            }
        },

        watch: {
            itemsOrg(items){
                this.items = items;
            }
        },

        methods: {
            /*getItems() {
                axios.get(this.baseUri + this.uri).then(response => {
                    this.items = response.data;
                });
            },*/

            update: function (item) {
                this.$emit('update', item);
            },

            destroy: function (item) {
                this.$emit('destroy', item);
            },

            // -------------------------------------
            //  D&D events
            // -------------------------------------
            dragstart(event){
                // If dataTransfer is not set, the other events are not fired !
                event.dataTransfer.setData("id", event.target.id);

                this.dragging = true;

                this.sourceRow = $(event.target).closest('.tree-row');
                this.sourceId = this.sourceRow.attr('id');
                
                this.placeholder.insertBefore(this.sourceRow);
                this.sourceRow.hide();

                //this.placeholder.empty();
                //this.draggedRow.clone().appendTo(this.placeholder);

                //console.log('! dragstart', this.sourceId);
            },

            drag(event){
                
            },

            dragmove(event){
                // Adjusting the depth can be done even when not hovering target
                this.updateDragX(event);
            },

            dragend(event){
                console.log('id: ' + this.sourceId + ', parent_id: ' + this.dragParentId + ', position: ' + this.dragPosition);

                var data = {
                    sourceId: this.sourceId,
                    parentId: this.dragParentId,
                    position: this.dragPosition
                };

                axios.put(this.baseUri + this.uri + '-position', data).then(response => {
                    this.sourceRow.show();
                    this.$emit('updated');
                    this.resetTarget();
                    this.dragging = false;
                }).catch(response => {
                    this.dragging = false;
                });
            },

            dragenter(event){
                var target = $(event.target).closest('.tree-row');
                var targetId = target.attr('id');

                if(targetId == this.sourceId){
                    // Nothing to do on itself !
                }
                else if(targetId == this.targetId){
                    // Nothing to do : already on this element (this can be triggered by children)
                }
                else{
                    this.targetRow = target;
                    this.targetId = targetId;
                    this.targetDepth = parseInt(target.closest('.tree-group').attr('depth'));
                    //console.log('! dragenter', this.targetId);
                }
            },

            dragover(event){
                event.preventDefault();
                this.updateDragY(event);
            },

            dragleave(event){

            },

            drop(event){
                
            },

            resetTarget(){
                this.targetRow = null;
                this.targetId = null;
                this.targetDepth = 0;

                this.dragX = 0;
                this.dragY = 0;
                this.dragBefore = null;
                this.dragDepth = 0;
            },

            updateDragY(event){

                if(this.targetRow){

                    // Position before or after target element :

                    var h = this.targetRow.outerHeight();
                    var y = h - (event.pageY - this.targetRow.offset().top) - Math.floor(h / 2);

                    if((y >= 0 && this.dragY < 0) || (y < 0 && this.dragY >= 0)){
                        this.dragY = y;
                        this.dragBefore = (this.dragY >= 0);
                        if(this.dragBefore){
                            this.placeholder.insertBefore(this.targetRow);
                        }else{
                            this.placeholder.insertAfter(this.targetRow);
                        }
                        this.updateTarget();
                        //console.log('! dragover', 'before', this.dragBefore);
                    }
                }
            },

            updateDragX(event){

                // The point here is to determine the depth according to the currently hovered element
                // (found by updateDragY) and to the mouse horizontal position.

                if(this.options['nested'] && this.targetRow){
                    
                    var depth = 0;

                    // Positionning before a row : same parent !
                    if(this.dragBefore){
                        depth = this.targetDepth;
                    }
                    else{
                        // Find the theoric depth according to the  mouse position :
                        var x = event.pageX - this.targetRow.offset().left;
                        if(x >= this.depthOffset){
                            var depth = Math.floor((x - this.depthOffset) / this.depthIndent) + 1;
                        }

                        // Limit the depth to +1 level under the currently hovered row :
                        depth = Math.min(depth, this.targetDepth + 1);

                        // Find out if the hovered row has children :
                        var targetHasChildren = false;
                        var childrenGroup = this.targetRow.next();
                        if(childrenGroup.length > 0 && childrenGroup.hasClass('.tree-group'))
                        {
                            var children = childrenGroup.find('> .tree-row');
                            if(children.length > 0){
                                targetHasChildren = true;
                            }
                            if(children.length == 1 && children.first().attr('id') == this.sourceId){
                                targetHasChildren = false;
                            }
                        }
                        // If the hovered row has children, it must be the first of them :
                        if(targetHasChildren){
                            depth = this.targetDepth + 1;
                        }
                        // No children : it can be one or more steps above :
                        else{
                            var targetIsLastChild = true;
                            var target = this.targetRow;
                            var group = target.closest('.tree-group');
                            var minDepth = parseInt(group.attr('depth'));

                            while(targetIsLastChild){
                                var children = group.find('> .tree-row');
                                targetIsLastChild = ((children.index(target) + 1) == children.length);

                                if(targetIsLastChild){
                                    target = group.prev();
                                    if(target.length && target.hasClass('.tree-row')){
                                        group = target.closest('.tree-group');
                                        minDepth = parseInt(group.attr('depth'));
                                    }
                                    else{
                                        targetIsLastChild = false;
                                    }
                                }
                            }
                            depth = Math.max(depth, minDepth);
                        }
                    }

                    this.dragDepth = depth;
                    this.updateTarget();
                    
                    //console.log('! dragover', 'depth', this.dragDepth, 'targetDepth', this.targetDepth);
                }
            },

            updateTarget(){
                this.dragParentId = 0;
                this.dragPosition = 0;

                // On se place au-dessus d'une ligne : même parent !
                if(this.dragBefore){
                    this.dragParentId = parseInt(this.targetRow.attr('parent-id'));
                    this.dragPosition = parseInt(this.targetRow.attr('position'));
                }
                // On se place en-dessous d'une ligne, 3 cas :
                // - Même niveau : même parent et même position + 1
                // - Un niveau plus bas : la ligne survolée est le parent et position 0
                // - Un ou plusieurs niveaux plus haut : remonter les groupes...
                else{
                    // Même niveau
                    if(this.dragDepth == this.targetDepth){
                        this.dragParentId = parseInt(this.targetRow.attr('parent-id'));
                        this.dragPosition = parseInt(this.targetRow.attr('position')) + 1;
                        
                        // Attention au décalage de position lié à l'élément déplacé (uniquement pour déplacement au même niveau)
                        var sourceParentId = parseInt(this.sourceRow.attr('parent-id'));
                        var sourcePosition = parseInt(this.sourceRow.attr('position'));

                        if((sourceParentId == this.dragParentId) && (this.dragPosition > sourcePosition)){
                            --this.dragPosition;
                        }
                    }
                    // Un niveau plus bas niveau
                    else if(this.dragDepth > this.targetDepth){
                        this.dragParentId = this.targetId;
                        this.dragPosition = 0;
                    }
                    // Un ou plusieurs niveaux plus haut
                    else if(this.dragDepth < this.targetDepth){

                    }
                }
            }
        }
    }
</script>
