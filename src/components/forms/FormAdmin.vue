<template>
    <div class="form-admin form-horizontal">
        
        <input-wrapper 
            :name="uniqId + 'name'"
            :error="errors.name"
            label="Nom">
            <div slot="input">
                <input-text
                    v-model="item.name"
                    :name="uniqId + 'name'"
                    label="Nom"
                    :error="errors.name"
                    @input="updated">
                </input-text>
            </div>
        </input-wrapper>

        <input-wrapper 
            :name="uniqId + 'email'"
            :error="errors.email"
            label="Email">
            <div slot="input">
                <input-email
                    v-model="item.email"
                    :name="uniqId + 'email'"
                    label="Email"
                    :error="errors.email"
                    @input="updated">
                </input-email>
            </div>
        </input-wrapper>

        <input-wrapper 
            :name="uniqId + 'password'"
            :error="errors.password"
            label="Mot de passe">
            <div slot="input">
                <input-password
                    v-model="item.password"
                    :name="uniqId + 'password'"
                    label="Mot de passe"
                    placeholder=""
                    :error="errors.password"
                    @input="updated">
                </input-password>
            </div>
        </input-wrapper>

        <input-wrapper 
            :name="uniqId + 'activated'"
            :error="errors.activated"
            label="Activé">
            <div slot="input">
                <input-checkbox
                    v-model="item.activated"
                    :name="uniqId + 'activated'"
                    :error="errors.activated"
                    @input="updated">
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
