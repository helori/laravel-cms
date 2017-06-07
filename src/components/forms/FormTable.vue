<template>
    <div class="form-table form-horizontal">

        <input-wrapper 
            :name="uniqId + 'name'"
            :error="errors.name"
            label="Nom">
            <div slot="input">
                <input-text
                    v-model="item.name"
                    :name="uniqId + 'name'"
                    :error="errors.name"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper>

        <input-wrapper 
            :name="uniqId + 'alias'"
            :error="errors.alias"
            label="Identifiant"
            help="Identifiant utilisé pour retrouver l'élément">
            <div slot="input">
                <input-text
                    v-model="item.alias"
                    :name="uniqId + 'alias'"
                    :error="errors.alias"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper>

        <input-wrapper 
            :name="uniqId + 'table'"
            :error="errors.table"
            label="Nom de la table">
            <div slot="input">
                <input-text
                    v-model="item.table"
                    :name="uniqId + 'table'"
                    placeholder=""
                    :error="errors.table"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper>

        <input-wrapper 
            :name="uniqId + 'in_admin'"
            :error="errors.in_admin"
            label="Administrable">
            <div slot="input">
                <input-checkbox 
                    v-model="item.in_admin"
                    :name="uniqId + 'in_admin'">
                </input-checkbox>
            </div>
        </input-wrapper>

        <input-wrapper 
            :name="uniqId + 'multiple'"
            :error="errors.multiple"
            label="Éléments multiples">
            <div slot="input">
                <input-checkbox 
                    v-model="item.multiple"
                    :name="uniqId + 'multiple'">
                </input-checkbox>
            </div>
        </input-wrapper>     

    </div>
</template>

<script>
    import crudFormMixin from '../crud/CrudFormMixin.js'
    export default {
        mixins: [crudFormMixin]
    }
</script>
