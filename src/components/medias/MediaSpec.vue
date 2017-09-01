<style scoped>

</style>

<template>

    <div class="spec" v-if="item">
        <div>Type : {{ item.mime }}</div>
        <div v-if="item.size < 1000000">Poids : {{ item.size / 1000 }} ko</div>
        <div v-else>Poids : {{ item.size / 1000000 }} Mo</div>
        <div v-if="item.mime && item.mime.indexOf('image') !== -1">Taille : {{ item.width }} x {{ item.height }} px</div>
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
