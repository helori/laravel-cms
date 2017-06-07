<style scoped>
    
</style>

<template>
    
    <input 
        type="text"
        :id="name"
        class="form-control"
        v-model="dataValue"
        placeholder="YYYY-MM-DD"
        pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
        @change="updateValue" />

</template>

<script>
    import inputMixin from '../crud/InputMixin.js'
    export default {
        mixins: [inputMixin],
        data(){
            return{
                dataValue: this.$options.filters.date(new Date(this.value), '-', 'Y-m-d')
            };
        },
        watch: {
            value: {
                handler: function (val) {
                    this.dataValue = this.$options.filters.date(new Date(val), '-', 'Y-m-d');
                }
            }
        }
    }
</script>

