<template>
    <div class="alert alert-danger">
        <h1 class="h4">Erreur {{ code }}</h1>
        <p>{{ message }}</p>
    </div>
</template>

<script>
    export default {
        props: {
            code: {
                type: Number,
                default: 404
            },

            message: {
                type: String,
                default: "Oups ! Cette page n'existe pas."
            }
        },
    }
</script>