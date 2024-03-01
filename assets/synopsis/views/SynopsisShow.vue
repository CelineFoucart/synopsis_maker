<template>
    <article :class="{'alert alert-danger': error}">
        <h1 class="h5" v-if="error">Erreur 404</h1>
        <p v-if="error">Cette page n'existe pas.</p>
        <Header v-if="!loading && synopsisStore.synopsis !== null" :title="synopsisStore.synopsis.title" :id="synopsisStore.synopsis.id" />
    </article>
    <Loading v-if="loading"></Loading>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { createToastify } from '&utils/toastify.js';
import Loading from '&utils/Loading.vue';
import Header from '&synopsis/components/synopsis_show/Header.vue';

export default {
    name: 'SynopsisShow',

    components: {
        Loading,
        Header
    },

    data() {
        return {
            error: false,
            loading: false
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),
    },
    
    async mounted () {
        if (this.synopsisStore.synopsis !== null) {
            return;
        }

        this.loading = true;
        this.status = await this.synopsisStore.getSynopsis(this.$route.params);
        if (!this.status) {
            createToastify("Ce synopsis n'existe pas.", 'error');
            this.error = true;
        }
        this.loading = false;
    },
}
</script>