<template>
    <div class="form-horizontal" v-if="options.table">

        <div v-for="field in options.table.fields">
            <input-wrapper  
                v-if="field.create"
                :name="uniqId + field.name"
                :error="errors[field.name]"
                :label="field.title">

                <div slot="input">

                    <input-text
                        v-if="field.type == 'text'"
                        v-model="item[field.name]"
                        :name="uniqId + field.name"
                        :error="errors[field.name]"
                        @input="updated">
                    </input-text>

                    <input-email
                        v-if="field.type == 'email'"
                        v-model="item[field.name]"
                        :name="uniqId + field.name"
                        :error="errors[field.name]"
                        @input="updated">
                    </input-email>

                    <input-date
                        v-if="field.type == 'date'"
                        v-model="item[field.name]"
                        :name="uniqId + field.name"
                        :error="errors[field.name]"
                        @input="updated">
                    </input-date>

                    <input-number
                        v-if="field.type == 'number'"
                        v-model="item[field.name]"
                        :name="uniqId + field.name"
                        :error="errors[field.name]"
                        @input="updated">
                    </input-number>

                    <input-password
                        v-if="field.type == 'password'"
                        v-model="item[field.name]"
                        :name="uniqId + field.name"
                        :error="errors[field.name]"
                        @input="updated">
                    </input-password>

                    <input-select
                        v-if="field.type == 'select'"
                        v-model="item[field.name]"
                        :name="uniqId + field.name"
                        :error="errors[field.name]"
                        :options="options[field.name]"
                        @input="updated">
                    </input-select>

                </div>

            </input-wrapper>
        </div>

    </div>
</template>

<script>
    import crudFormMixin from 'vue-crud/src/crud/CrudFormMixin.js'
    export default {
        mixins: [crudFormMixin]
    }
</script>
