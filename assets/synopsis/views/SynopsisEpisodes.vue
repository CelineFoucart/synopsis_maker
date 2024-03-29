<template>
    <div>
        <Error v-if="error"></Error>
        <article v-if="!loading && synopsisStore.synopsis !== null">
            <HeaderSynopsis :synopsis="synopsisStore.synopsis"></HeaderSynopsis>
            <div class="border-top border-bottom my-3 p-2">
                <div class="row">
                    <div class="col-md-8">
                        <h2 class="h5 mb-0 text-secondary">Ajoutez des épisodes et des chapitres puis réordonnez-les pour concevoir le synopsis</h2>
                    </div>
                    <div class="col-md-4 text-end">
                        <button href="#" class="btn btn-sm btn-dark collapsed me-1" v-tooltip="'Afficher la légende'" data-bs-toggle="collapse" data-bs-target="#collapseComment" aria-expanded="true" aria-controls="collapseComment">
                            <i class="fas fa-comment" aria-hidden="true"></i> 
                        </button>
                        <button class="btn btn-sm btn-dark me-1" @click.prevent="openAll = !openAll" v-tooltip="openAll ? 'Tout ouvrir' : 'Tout fermer'">
                            <i class="fa-solid fa-folder-open fa-fw" v-if="!openAll"></i>
                            <i class="fa-solid fa-folder-closed fa-fw" v-if="openAll"></i>
                        </button>
                        <router-link class="btn btn-sm btn-dark me-1" v-tooltip="'Archives'" :to="{ name: 'SynopsisArchive', params:{slug: synopsisStore.synopsis.slug, id: synopsisStore.synopsis.id} }">
                            <i class="fa-solid fa-box-archive fa-fw"></i>
                        </router-link>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-dark" v-tooltip="'Ajouter un chapitre'" @click="openChapterModal">
                                <i class="fa-solid fa-folder-plus fa-fw"></i>
                            </button>
                            <button class="btn btn-sm btn-dark" v-tooltip="'Ajouter un épisode'" @click="onAppendEpisode(null)">
                                <i class="fa-solid fa-file-circle-plus fa-fw"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="collapse" id="collapseComment">
                <label for="comment">Vous pouvez ajouter des remarques et des commentaires pour vous aider dans votre travail</label>
                <div class="input-group">
                    <textarea class="form-control" id="comment" v-model="legend"></textarea>
                    <button class="btn btn-success" v-tooltip="'Sauvegarder'" @click="saveLegend">
                        <i class="fa-solid fa-floppy-disk fa-fw"></i>
                    </button>
                </div>
            </div>
            <SynopsisElementList :openAll="openAll" :archived="false"></SynopsisElementList>
            
        </article>
        <Loading v-if="loading"></Loading>
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
import SynopsisElementList from '&synopsis/components/synopsis_show/SynopsisElementList.vue';

export default {
    name: 'SynopsisEpisodes',

    components: {
        Loading,
        HeaderSynopsis,
        Error,
        SynopsisElementList
    },

    data() {
        return {
            error: false,
            legend: null,
            loading: false,
            openAll: true,
            chapterToEdit: { title: null, description: null, color: null, content: null, id: null },
            episodeToEdit: {},
            chapterModal: false,
            episodeModal: false,
            archiveModal: false,
            elementToArchive: { title: null, id: null, archived: false, type: null }
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
        const status = await this.synopsisStore.getSynopsis(this.$route.params);
        if (!status) {
            createToastify("Ce synopsis n'existe pas.", 'error');
            this.error = true;
        } else {
            this.legend = this.synopsisStore.synopsis.legend;
        }

        const statusCategory = await this.categoryStore.getCategories();
        if (!statusCategory) {
            createToastify("Le chargement des catégories a échoué.", 'error');
        }
        
        this.loading = false;
    },

    methods: {
        saveLegend() {
            const status = this.synopsisStore.putSynopsisLegend(this.synopsisStore.synopsis.id, {legend: this.legend});
            if (!status) {
                createToastify('La sauvegarde a échoué.', 'error');
            }
        },
    },
}
</script>

<style scoped>
#collapseComment div {
    white-space: pre-wrap
}
</style>