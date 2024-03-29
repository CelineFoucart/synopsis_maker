<template>
    <article class="card shadow-sm bg-light" :style="{'border-color': chapter.color ? chapter.color : 'rgba(0, 0, 0, 0.176' }">
        <div class="card-body">
            <header class="row">
                <div class="col-9">
                    <h2 class="h5">
                        <span class="handle" v-if="archived === false"><i class="fa-solid fa-fw fa-arrows-up-down-left-right"></i></span>
                        {{ chapter.title }}
                        <i class="fa-solid fa-box-archive fa-fw button" v-tooltip="'Archivé'" v-if="chapter.archived === true"></i>
                    </h2>
                </div>
                <div class="col-3 fs-5 justify-content-end align-items-center d-flex gap-1">
                    <span class="button" @click.prevent="showComment = !showComment">
                        <i class="fa-solid fa-comment fa-fw" v-if="!showComment" v-tooltip="'Afficher la description'"></i>
                        <i class="fa-solid fa-comment-slash fa-fw" v-if="showComment" v-tooltip="'Cacher la description'"></i>
                    </span>
                    
                    <span class="button" @click.prevent="isOpen = !isOpen">
                        <i class="fa-solid fa-folder-open fa-fw" v-if="!isOpen" v-tooltip="'Ouvrir'"></i>
                        <i class="fa-solid fa-folder-closed fa-fw" v-if="isOpen" v-tooltip="'Fermer'"></i>
                    </span>
                    <i class="fa-solid fa-file-circle-plus button fa-fw" v-tooltip="'Ajouter un épisode'" @click="$emit('on-append', chapter)" v-if="archived === false"></i>
                    <i class="fa-solid fa-box-archive fa-fw button" v-tooltip="'Archive'" @click="$emit('on-archive', chapter)"></i>
                    <i class="fa-solid fa-pen fa-fw button" v-tooltip="'Editer'" @click="$emit('on-edit', chapter)"></i>
                    <i class="fa-solid fa-trash fa-fw button text-danger" v-tooltip="'Supprimer'" @click="deleteModal = true"></i>
                </div>
                <div class="col-12" v-if="chapter.description && showComment">
                    <p class="small mb-0" style="white-space: pre-wrap;">{{ chapter.description }}</p>
                </div>
            </header>
            <div class="row g-2 sortable-list" v-if="isOpen" :data-list="chapter.id">
                <div class="col-md-4 col-lg-3" v-for="episode in episodes" :key="episode.id" :data-id="episode.id">
                    <EpisodeCard :archived="archived" :episode="episode" @on-archive-episode="onArchiveEpisode" @on-edit-episode="onEditEpisode"></EpisodeCard>
                </div>
            </div>
        </div>
    </article>
    <Delete :title="chapter.title" :loading="loading" v-if="deleteModal" @on-confirm="onDelete" @on-cancel="deleteModal = false"></Delete>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import Delete from '&utils/Delete.vue';
import EpisodeCard from '&synopsis/components/synopsis_show/EpisodeCard.vue';

export default {
    name: 'ChapterCard',

    components: {
        Delete,
        EpisodeCard
    },

    emits: ['on-edit', 'on-append', 'on-edit-episode', 'on-archive-episode', 'on-archive'],

    props: {
        chapter: Object,
        openAll: Boolean,
        archived: {
            type: Boolean,
            default: false
        },
    },

    data() {
        return {
            isOpen: true,
            showComment: false,
            deleteModal: false,
            loading: false
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),

        episodes() {
            const episodes = [];

            this.chapter.episodes.forEach(episode => {
                if (episode.archived === this.archived || (episode.archived === null && this.archived === false)) {
                    episodes.push(episode);
                }
            });

            return episodes;
        }
    },

    watch: {
        openAll() {
            this.isOpen = this.openAll;
        }
    },

    methods: {
        async onDelete() {
            this.loading = true;
            await this.synopsisStore.deleteChapter(this.chapter.id);
            this.loading = false;
        },

        onEditEpisode(episode) {
            episode.chapter = this.chapter;
            this.$emit('on-edit-episode', episode);
        },

        onArchiveEpisode(episode) {
            this.$emit('on-archive-episode', episode);
        },

        onDragover(e) {
            e.preventDefault();
        },
    },
}
</script>

<style scoped>
.button {
    cursor: pointer;
}
</style>