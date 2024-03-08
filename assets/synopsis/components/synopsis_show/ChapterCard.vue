<template>
    <article class="card shadow-sm bg-light" :style="{'border-color': chapter.color ? chapter.color : 'rgba(0, 0, 0, 0.176' }">
        <div class="card-body">
            <header class="row">
                <div class="col-9 d-flex gap-1">
                    <i class="fa-solid fa-circle fa-fw h5 pt-1" :style="{color: chapter.color ? chapter.color : '#000' }"></i>
                    <div>
                        <h2 class="h5">{{ chapter.title }}</h2>
                        <p class="small mb-0" style="white-space: pre-wrap;" v-if="chapter.description && showComment">{{ chapter.description }}</p>
                    </div>
                </div>
                <div class="col-3 text-end">
                    <span class="button" @click.prevent="showComment = !showComment">
                        <i class="fa-solid fa-comment fa-fw me-1" v-if="!showComment" v-tooltip="'Afficher la description'"></i>
                        <i class="fa-solid fa-comment-slash fa-fw me-1" v-if="showComment" v-tooltip="'Cacher la description'"></i>
                    </span>
                    
                    <span class="button" @click.prevent="isOpen = !isOpen">
                        <i class="fa-solid fa-folder-open fa-fw me-1" v-if="!isOpen" v-tooltip="'Ouvrir'"></i>
                        <i class="fa-solid fa-folder-closed fa-fw me-1" v-if="isOpen" v-tooltip="'Fermer'"></i>
                    </span>
                    <i class="fa-solid fa-file-circle-plus button fa-fw me-1" v-tooltip="'Ajouter un épisode'" @click="$emit('on-append', chapter)"></i>
                    <i class="fa-solid fa-pen fa-fw button me-1" v-tooltip="'Editer'" @click="$emit('on-edit', chapter)"></i>
                    <i class="fa-solid fa-trash fa-fw button text-danger" v-tooltip="'Supprimer'" @click="deleteModal = true"></i>
                </div>
            </header>
            <div class="row g-2" v-if="isOpen">
                <div class="col-md-4 col-lg-3" v-for="episode in chapter.episodes">
                    <EpisodeCard :episode="episode"></EpisodeCard>
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

    emits: ['on-edit', 'on-append'],

    props: {
        chapter: Object,
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
    },

    methods: {
        async onDelete() {
            this.loading = true;
            await this.synopsisStore.deleteChapter(this.chapter.id);
            this.loading = false;
        }
    },
}
</script>

<style scoped>
.button {
    cursor: pointer;
}
</style>