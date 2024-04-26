<template>
    <div>
        <Error v-if="error"></Error>
        <article v-if="synopsisStore.synopsis !== null">
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
            <aside class="d-flex gap-3 justify-content-end">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="chapterDesc" v-model="showChapterDescription">
                    <label class="form-check-label" for="chapterDesc">Descriptions des chapitres</label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="episodeDesc" v-model="showEpisodeDescription">
                    <label class="form-check-label" for="episodeDesc">Descriptions des épisodes</label>
                </div>
            </aside>
            <SynopsisElementList 
                :openAll="openAll" 
                :archived="false"
                :showChapterDescription="showChapterDescription"
                :showEpisodeDescription="showEpisodeDescription"
                @on-edit-chapter="onEditChapter"
                @on-edit-episode="onEditEpisode"
                @on-append-episode="onAppendEpisode">
            </SynopsisElementList>
        </article>
        <ChapterModal :chapter="chapterToEdit" v-if="chapterModal" @on-close="chapterModal = false"></ChapterModal>
        <EpisodeModal :episode="episodeToEdit" v-if="episodeModal" @on-close="episodeModal = false"></EpisodeModal>
    </div>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { useCategoryStore } from '&synopsis/stores/category.js';
import { createToastify } from '&utils/toastify.js';
import Error from '&utils/Error.vue';
import HeaderSynopsis from '&synopsis/components/synopsis_show/HeaderSynopsis.vue';
import SynopsisElementList from '&synopsis/components/synopsis_show/SynopsisElementList.vue';
import ChapterModal from '&synopsis/components/synopsis_show/ChapterModal.vue';
import EpisodeModal from '&synopsis/components/synopsis_show/EpisodeModal.vue';

export default {
    name: 'SynopsisEpisodes',

    components: {
        HeaderSynopsis,
        Error,
        SynopsisElementList,
        ChapterModal,
        EpisodeModal,
    },

    data() {
        return {
            error: false,
            legend: null,
            openAll: true,
            chapterToEdit: { title: null, description: null, color: null, content: null, id: null },
            episodeToEdit: {},
            chapterModal: false,
            episodeModal: false,
            archiveModal: false,
            elementToArchive: { title: null, id: null, archived: false, type: null },
            showEpisodeDescription: false,
            showChapterDescription: false
        }
    },

    computed: {
        ...mapStores(useSynopsisStore, useCategoryStore),
    },

    async mounted () {
        if (this.synopsisStore.synopsis !== null) {
            return;
        }
        
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
    },

    methods: {
        saveLegend() {
            const status = this.synopsisStore.putSynopsisLegend(this.synopsisStore.synopsis.id, {legend: this.legend});
            if (!status) {
                createToastify('La sauvegarde a échoué.', 'error');
            }
        },

        openChapterModal() {
            this.chapterToEdit = { title: null, description: null, color: null, id: null };
            this.chapterModal = true;
        },

        onEditChapter(chapter) {
            this.chapterToEdit = chapter;
            this.chapterModal = true;            
        },

        onEditEpisode(episode) {
            if (episode.chapter === undefined) {
                episode.chapter = null;
            }
            this.episodeToEdit = episode;
            this.episodeModal = true;
        },
        
        onAppendEpisode(chapter = null) {
            this.episodeToEdit = { 
                id: null, 
                title: null, 
                description: null, 
                color: null, 
                content: null, 
                places: [], 
                characters: [], 
                chapter: chapter 
            };
            this.episodeModal = true;
        },
    },
}
</script>

<style scoped>
#collapseComment div {
    white-space: pre-wrap
}
</style>