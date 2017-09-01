<style scoped>
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
</style>

<template>

    <div class="preview">
        <div v-if="item">
            <div class="image"
                v-if="item.mime && item.mime.indexOf('image') !== -1"
                :style="'background-image:url(/' + item.filepath + '?' + item.decache + ')'">
            </div>
            <video controls v-else-if="item.mime && item.mime.indexOf('video') !== -1">
                <source :src="'/' + item.filepath + '?' + item.decache" :type="item.mime" />
            </video>
            <div class="text-wrapper" v-else>
                <div class="text">
                    <div>{{ item.mime }}</div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

    export default {

        data(){
            return{
                item: this.media
            };
        },

        props: {
            media: {
                required: true
            }
        },

        watch: {
            media: {
                handler(media){
                    this.item = media;
                }
            },
        },
    }
</script>
