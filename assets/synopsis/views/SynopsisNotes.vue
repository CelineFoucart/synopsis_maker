<template>
    <Error v-if="error"></Error>
    <article v-if="!loading && synopsisStore.synopsis !== null">
        <HeaderSynopsis :synopsis="synopsisStore.synopsis" @on-delete="deleteSynopsis"></HeaderSynopsis>
        
        <section class="mt-4">
            <h2 class="h5">Notes, remarques et réflexions sur ce synopsis</h2>
            <Description :data="synopsisStore.synopsis.notes" @on-save="onSave"></Description>
        </section>
    </article>
    <Loading v-if="loading || synopsisStore.loading"></Loading>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { createToastify } from '&utils/toastify.js';
import Loading from '&utils/Loading.vue';
import Error from '&utils/Error.vue';
import HeaderSynopsis from '&synopsis/components/synopsis_show/HeaderSynopsis.vue';
import Description from '&synopsis/components/synopsis_show/Description.vue';

export default {
    name: 'SynopsisNotes',

    components: {
        Loading,
        HeaderSynopsis,
        Description,
        Error,
    },

    data() {
        return {
            error: false,
            loading: false,
            partialLoading: false
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

    methods: {
        async onSave(description) {
            const status = await this.synopsisStore.putSynopsisNotes(description, this.synopsisStore.synopsis.id);
            if (!status) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
            }
        },

        async deleteSynopsis() {
            this.loading = true;
            const status = await this.synopsisStore.deleteSynopsis(this.synopsisStore.synopsis.id);
            if (status) {
                createToastify('Le synopsis a été supprimé.', 'success');
                this.$router.push('/synopsis');
            } else {
                createToastify('La suppression a échoué.', 'error');
            }
            this.loading = false;
        }
    },
}
</script>

<style scoped>

</style>