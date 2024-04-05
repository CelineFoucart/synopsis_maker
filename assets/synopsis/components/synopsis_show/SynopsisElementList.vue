<template>
    <div class="position-relative">
        <div class="row g-3 pt-3 sortable-chapter">
            <div class="col-12" v-for="chapter in chapters" :key="chapter.id" :data-id="chapter.id">
                <ChapterCard :openAll="openAll" :chapter="chapter" :archived="archived"
                    @on-archive-episode="onArchiveEpisode" 
                    @on-edit-episode="onEditEpisode" 
                    @on-edit="onEditChapter" 
                    @on-archive="onArchiveChapter"
                    @on-append="onAppendEpisode">
                </ChapterCard>
            </div>
        </div>
        <div class="row g-3 m-2 sortable-list" style="min-height: 167px;" data-list="0">
            <div class="col-md-4 col-lg-3" v-for="episode in episodesWithNoChapter" :key="episode.id" :data-id="episode.id">
                <EpisodeCard @on-archive-episode="onArchiveEpisode" @on-edit-episode="onEditEpisode" :archived="archived" :episode="episode"></EpisodeCard>
            </div>
        </div>
        
        <Loading v-if="synopsisStore.loading"></Loading>
        <ChapterModal :chapter="chapterToEdit" v-if="chapterModal" @on-close="chapterModal = false"></ChapterModal>
        <EpisodeModal :episode="episodeToEdit" v-if="episodeModal" @on-close="episodeModal = false"></EpisodeModal>
        <ArchiveModal :elementToArchive="elementToArchive"  @on-close="archiveModal = false" v-if="archiveModal"></ArchiveModal>
    </div>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { useCategoryStore } from '&synopsis/stores/category.js';
import { createToastify } from '&utils/toastify.js';
import ChapterCard from '&synopsis/components/synopsis_show/ChapterCard.vue';
import EpisodeCard from '&synopsis/components/synopsis_show/EpisodeCard.vue';
import ChapterModal from '&synopsis/components/synopsis_show/ChapterModal.vue';
import EpisodeModal from '&synopsis/components/synopsis_show/EpisodeModal.vue';
import ArchiveModal from '&synopsis/components/synopsis_show/ArchiveModal.vue';
import Loading from '&utils/Loading.vue';
import Sortable from 'sortablejs';

export default {
    name: 'SynopsisElementList',

    components: {
        Loading,
        ChapterCard,
        EpisodeCard,
        ChapterModal,
        EpisodeModal,
        ArchiveModal
    },

    props: {
        archived: {
            type: Boolean,
            default: false
        },
        openAll: {
            type: Boolean,
            default: true
        },
    },

    data() {
        return {
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

        chapters() {
            const chapters = [];

            this.synopsisStore.synopsis.chapters.forEach(chapter => {
                if (this.isAvailable(chapter)) {
                    chapters.push(chapter);
                } else if(this.archived) {
                    let withArchivedEpisode = false;
                    chapter.episodes.forEach(episode => {
                        if (episode.archived) {
                            withArchivedEpisode = true;
                        }
                    });

                    if (withArchivedEpisode) {
                        chapters.push(chapter);
                    }
                }
            });

            return chapters;
        },

        episodesWithNoChapter() {
            const episodes = [];

            if (this.synopsisStore.synopsis === null) {
                return episodes;
            }

            this.synopsisStore.synopsis.episodes.forEach(episode => {
                if (episode.chapterId === null && this.isAvailable(episode)) {
                    episodes.push(episode);
                }
            });

            return episodes;
        }
    },

    mounted () {
        if (this.archived === true) {
            return;
        }

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
    },

    methods: {
        isAvailable(element) {
            if (this.archived) {
                return element.archived === true;
            }

            return element.archived !== true;
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

        onArchiveEpisode(episode) {
            this.elementToArchive = { title: episode.title, id: episode.id, archived: episode.archived, type: 'episode'};
            this.archiveModal = true;
        },

        onArchiveChapter(chapter) {
            this.elementToArchive = { title: chapter.title, id: chapter.id, archived: chapter.archived, type: 'chapter'};
            this.archiveModal = true;
        },

        onAppendEpisode(chapter = null) {
            this.episodeToEdit = { id: null, title: null, description: null, color: null, content: null, chapter: chapter };
            this.episodeModal = true;
        },
    },
}
</script>

<style scoped>

</style>