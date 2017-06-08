<template>
    <div class="form-horizontal" v-if="options.table">

        <div v-for="field in options.table.fields">
            <input-wrapper  
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

                    <input-textarea
                        v-if="field.type == 'textarea'"
                        v-model="item[field.name]"
                        :name="uniqId + field.name"
                        :error="errors[field.name]"
                        @input="updated">
                    </input-textarea>

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

                    <input-checkbox
                        v-if="field.type == 'checkbox'"
                        v-model="item[field.name]"
                        :name="uniqId + field.name"
                        :error="errors[field.name]"
                        @input="updated">
                    </input-checkbox>

                    <input-editor
                        v-if="field.type == 'editor'"
                        v-model="item[field.name]"
                        :name="uniqId + field.name"
                        :error="errors[field.name]"
                        @input="updated">
                    </input-editor>

                    <input-media
                        v-if="field.type == 'media'"
                        v-model="item[field.name]"
                        :name="uniqId + field.name"
                        :error="errors[field.name]"
                        @input="updated">
                    </input-media>

                    <input-medias
                        v-if="field.type == 'medias'"
                        v-model="item[field.name]"
                        :name="uniqId + field.name"
                        :error="errors[field.name]"
                        @input="updated">
                    </input-medias>

                    <input-select
                        v-if="field.type == 'relation'"
                        v-model="item[field.name]"
                        :name="uniqId + field.name"
                        :error="errors[field.name]"
                        :options="options[field.name]"
                        @input="updated">
                    </input-select>

                    <div v-if="field.type == 'alias'" style="padding-top: 7px; font-size: 16px;">
                        {{ item[field.name] }}
                    </div>

                </div>

            </input-wrapper>
        </div>

    </div>
</template>

<script>
    import crudFormMixin from '../crud/CrudFormMixin.js'
    export default {
        mixins: [crudFormMixin]
    }
</script>
