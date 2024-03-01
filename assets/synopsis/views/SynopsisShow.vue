<template>
    <article :class="{'alert alert-danger': error}">
        <h1 class="h5" v-if="error">Erreur 404</h1>
        <p v-if="error">Cette page n'existe pas.</p>
        <HeaderSynopsis v-if="!loading && synopsisStore.synopsis !== null" :synopsis="synopsisStore.synopsis"></HeaderSynopsis>
    </article>
    <Description :data="synopsisStore.synopsis.description" v-if="!loading  && synopsisStore.synopsis !== null" @on-save="onSave"></Description>
    <Loading v-if="loading || partialLoading"></Loading>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { useCategoryStore } from '&synopsis/stores/category.js';
import { createToastify } from '&utils/toastify.js';
import Loading from '&utils/Loading.vue';
import HeaderSynopsis from '&synopsis/components/synopsis_show/HeaderSynopsis.vue';
import Description from '&synopsis/components/synopsis_show/Description.vue';

export default {
    name: 'SynopsisShow',

    components: {
        Loading,
        HeaderSynopsis,
        Description
    },

    data() {
        return {
            error: false,
            loading: false,
            partialLoading: false
        }
    },

    computed: {
        ...mapStores(useSynopsisStore, useCategoryStore),
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

        await this.categoryStore.getCategories();
        this.loading = false;
    },

    methods: {
        async onSave(description) {
            this.partialLoading = true;
            const data = {
                title: this.synopsisStore.synopsis.title, 
                pitch: this.synopsisStore.synopsis.pitch, 
                categories: this.synopsisStore.synopsis.categories,
                description: description
            };

            const status = await this.synopsisStore.putSynopsis(data, this.synopsisStore.synopsis.id);
            if (!status) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
            }
            this.partialLoading = false;
        }
    },
}
</script>