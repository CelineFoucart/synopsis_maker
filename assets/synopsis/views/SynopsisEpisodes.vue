<template>
    <div>
        <Error v-if="error"></Error>
        <article v-if="!loading && synopsisStore.synopsis !== null">
            <HeaderSynopsis :synopsis="synopsisStore.synopsis" @on-delete="deleteSynopsis"></HeaderSynopsis>
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

            <div class="row g-3 pt-3 sortable-chapter">
                <div class="col-12" v-for="chapter in synopsisStore.synopsis.chapters" :key="chapter.id" :data-id="chapter.id">
                    <ChapterCard :openAll="openAll" :chapter="chapter" @on-edit-episode="onEditEpisode" @on-edit="onEditChapter" @on-append="onAppendEpisode"></ChapterCard>
                </div>
            </div>
            <div class="row g-3 m-2 sortable-list" style="min-height: 167px;" data-list="0">
                <div class="col-md-4 col-lg-3" v-for="episode in episodesWithNoChapter" :key="episode.id" :data-id="episode.id">
                    <EpisodeCard @on-edit-episode="onEditEpisode" :episode="episode"></EpisodeCard>
                </div>
            </div>
        </article>
        <Loading v-if="loading || synopsisStore.loading"></Loading>
        <ChapterModal :chapter="chapterToEdit" v-if="chapterModal" @on-close="chapterModal = false"></ChapterModal>
        <EpisodeModal :episode="episodeToEdit" v-if="episodeModal" @on-close="episodeModal = false"></EpisodeModal>
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
import ChapterCard from '&synopsis/components/synopsis_show/ChapterCard.vue';
import EpisodeCard from '&synopsis/components/synopsis_show/EpisodeCard.vue';
import ChapterModal from '&synopsis/components/synopsis_show/ChapterModal.vue';
import EpisodeModal from '&synopsis/components/synopsis_show/EpisodeModal.vue';
import Sortable from 'sortablejs';

export default {
    name: 'SynopsisEpisodes',

    components: {
        Loading,
        HeaderSynopsis,
        Error,
        ChapterCard,
        EpisodeCard,
        ChapterModal,
        EpisodeModal
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
            episodeModal: false
        }
    },

    computed: {
        ...mapStores(useSynopsisStore, useCategoryStore),

        episodesWithNoChapter() {
            const episodes = [];

            if (this.synopsisStore.synopsis === null) {
                return episodes;
            }

            this.synopsisStore.synopsis.episodes.forEach(episode => {
                if (episode.chapterId === null) {
                    episodes.push(episode);
                }
            });

            return episodes;
        }
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

    updated() {
        document.querySelectorAll('.sortable-list').forEach(element => {
            new Sortable(element, {
                handle: '.handle',
                group: 'shared',
                ghostClass: 'blue-background-class',
                animation: 150,
                onEnd: async (evt) => {
                    const target = evt.to.dataset.list != 0 ? evt.to.dataset.list : null;
                    const episode = evt.item.dataset.id;
                    const position = evt.newIndex;
                    const status = await this.synopsisStore.dropEpisodeToChapter(episode, target, position);
                    if (!status) {
                        createToastify("L'opération a échoué.", "error")
                    }
                },
            });

            new Sortable(document.querySelector('.sortable-chapter'), {
                handle: '.handle',
                ghostClass: 'blue-background-class',
                animation: 150,
                onEnd: async (evt) => {
                    const chapter = evt.item.dataset.id;
                    const position = evt.newIndex;
                    const status = await this.synopsisStore.positionChapterAction(chapter, position);
                    if (!status) {
                        createToastify("L'opération a échoué.", "error")
                    }
                }
            })
        });
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
            this.episodeToEdit = { id: null, title: null, description: null, color: null, content: null, chapter: chapter };
            this.episodeModal = true;
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
#collapseComment div {
    white-space: pre-wrap
}

.text-secondary {
    color: #616a72;
}
</style>