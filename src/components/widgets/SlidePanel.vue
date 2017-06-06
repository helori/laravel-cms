<style scoped>
    .slide-panel{
        z-index: 100;
        position: fixed;
        top: 50px; bottom: 0;
        left: -100%;
        width: 100%;
        transition: all 0.3s linear;
    }
    .slide-overlay{
        position: absolute;
        z-index: 0;
        top: 0; bottom: 0;
        right: 0;
        width: 20%; height: 100%;
        background: transparent;
        transition: all 0.1s linear;
    }
    .slide-content{
        position: absolute;
        z-index: 1;
        top: 0; bottom: 0;
        left: 0;
        width: 80%; height: 100%;
        background: white;
        overflow-y: scroll;
        transition: all 0.3s linear;
        padding: 30px;
        box-shadow: 1px 0 3px rgba(0, 0, 0, 0.8);
    }
    .slide-panel.open{
        left: 0;
    }
    .slide-overlay.blur{
        background: rgba(0, 0, 0, 0.6);
    }
</style>

<template>
    
    <div class="slide-panel" :class="{'open': open}">
        <div class="slide-overlay" @click="setOpen(false)" :class="{'blur': blur}"></div>
        <div class="slide-content">
            <slot name="content"></slot>
        </div>
    </div>

</template>

<script>
    export default {
        data(){
            return{
                open: false,
                blur: false
            };
        },

        mounted() {
            
        },

        methods: {
            setOpen: function (open) {
                if(this.open !== open){
                    var self = this;
                    if(open){
                        this.open = open;
                        setTimeout(function(){
                            self.blur = true;
                        }, 300);
                    }else{
                        this.blur = false;
                        setTimeout(function(){
                            self.open = open;
                        }, 150);
                    }
                }
            }
        }
    }
</script>
