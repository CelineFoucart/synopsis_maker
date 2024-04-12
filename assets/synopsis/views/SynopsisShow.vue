<template>
    <Error v-if="error"></Error>
    <article v-if="!loading && synopsisStore.synopsis !== null">
        <HeaderSynopsis :synopsis="synopsisStore.synopsis" @on-delete="deleteSynopsis"></HeaderSynopsis>
        <p class="lead mt-3">{{ synopsisStore.synopsis.pitch }}</p>
        <section>
            <h2 class="h5 d-flex justify-content-between">
                Description
                <span class="text-success" role="button" @click="onSave" v-tooltip="'Sauvegarder'">
                    <i class="fa-solid fa-floppy-disk"></i>
                </span>
            </h2>
            <Description v-model:data="description"></Description>
        </section>
        <MetaData :element="synopsisStore.synopsis"></MetaData>
    </article>
    <Loading v-if="loading || partialLoading"></Loading>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { useCategoryStore } from '&synopsis/stores/category.js';
import { createToastify } from '&utils/toastify.js';
import Loading from '&utils/Loading.vue';
import Error from '&utils/Error.vue';
import HeaderSynopsis from '&synopsis/components/synopsis_show/HeaderSynopsis.vue';
import Description from '&utils/Description.vue';
import MetaData from '&synopsis/components/synopsis_show/MetaData.vue';

export default {
    name: 'SynopsisShow',

    components: {
        Loading,
        HeaderSynopsis,
        Description,
        Error,
        MetaData,
    },

    data() {
        return {
            error: false,
            loading: false,
            partialLoading: false,
            description: null
        }
    },

    computed: {
        ...mapStores(useSynopsisStore, useCategoryStore),
    },
    
    async mounted () {
        if (this.synopsisStore.synopsis !== null) {
            this.description = this.synopsisStore.synopsis.description;
            return;
        }

        this.loading = true;
        this.status = await this.synopsisStore.getSynopsis(this.$route.params);
        if (!this.status) {
            createToastify("Ce synopsis n'existe pas.", 'error');
            this.error = true;
        } else {
            this.description = this.synopsisStore.synopsis.description;
        }

        await this.categoryStore.getCategories();
        this.loading = false;
    },

    methods: {
        async onSave() {
            this.partialLoading = true;
            const data = {
                title: this.synopsisStore.synopsis.title, 
                pitch: this.synopsisStore.synopsis.pitch, 
                categories: this.synopsisStore.synopsis.categories,
                description: this.description
            };

            const status = await this.synopsisStore.putSynopsis(data, this.synopsisStore.synopsis.id);
            if (!status) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
            }
            this.partialLoading = false;
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
p {
    white-space: pre-wrap
}
</style>