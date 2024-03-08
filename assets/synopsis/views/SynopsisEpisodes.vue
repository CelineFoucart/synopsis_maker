<template>
    <div>
        <Error v-if="error"></Error>
        <article v-if="!loading && synopsisStore.synopsis !== null">
            <HeaderSynopsis :synopsis="synopsisStore.synopsis"></HeaderSynopsis>
            <div class="bg-light border-top border-bottom my-3 p-2">
                <div class="row">
                    <div class="col-md-8">
                        <h2 class="h5 mb-0 text-secondary">Ajoutez des épisodes et des chapitres puis réordonnez-les pour concevoir le synopsis</h2>
                    </div>
                    <div class="col-md-4 text-end">
                        <button href="#" class="btn btn-sm btn-dark collapsed me-1" v-tooltip="'Afficher la légende'" data-bs-toggle="collapse" data-bs-target="#collapseComment" aria-expanded="true" aria-controls="collapseComment">
                            <i class="fas fa-comment" aria-hidden="true"></i> 
                        </button>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-dark" v-tooltip="'Ajouter un chapitre'">
                                <i class="fa-solid fa-folder-plus fa-fw"></i>
                            </button>
                            <button class="btn btn-sm btn-dark" v-tooltip="'Ajouter un épisode'">
                                <i class="fa-solid fa-file-circle-plus fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="collapse" id="collapseComment">
                <div class="input-group">
                    <textarea class="form-control" v-model="legend"></textarea>
                    <button class="btn btn-success" v-tooltip="'Sauvegarder'" @click="saveLegend">
                        <i class="fa-solid fa-floppy-disk fa-fw"></i>
                    </button>
                </div>
            </div>
        </article>
        <Loading v-if="loading || partialLoading"></Loading>
    </div>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { useCategoryStore } from '&synopsis/stores/category.js';
import { createToastify } from '&utils/toastify.js';
import Loading from '&utils/Loading.vue';
import Error from '&utils/Error.vue';
import HeaderSynopsis from '&synopsis/components/synopsis_show/HeaderSynopsis.vue';

export default {
    name: 'SynopsisEpisodes',

    components: {
        Loading,
        HeaderSynopsis,
        Error
    },

    data() {
        return {
            error: false,
            legends: null,
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

        this.legend = this.synopsisStore.synopsis.legend;
        this.loading = false;
    },

    methods: {
        saveLegend() {
            this.partialLoading = true;

            const status = this.synopsisStore.putSynopsisLegend(this.synopsisStore.synopsis.id, {legend: this.legend});
            if (!status) {
                createToastify('La sauvegarde a échoué.', 'error');
            }

            this.partialLoading = false;
        }
    },
}
</script>

<style scoped>
#collapseComment div {
    white-space: pre-wrap
}

.text-secondary {
    color: #616a72;
}
</style>