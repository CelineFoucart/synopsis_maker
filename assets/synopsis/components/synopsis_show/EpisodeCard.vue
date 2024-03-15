<template>
    <article class="card h-100" :style="{'border-color': episode.color ? episode.color : 'rgba(0, 0, 0, 0.176' }">
        <div class="card-body">
            <h3 class="card-title h5 fw-bold">
                <i class="fas fa-circle" :style="{color: episode.color ? episode.color : '#000' }"></i>
                {{ episode.title }}
                <i class="fas fa-check-circle text-success" aria-label="Validé" v-if="episode.valid"></i>
            </h3>
            <p style="white-space: pre-wrap;">{{ episode.description }}</p>
        </div>
        <footer class="card-footer">
            <div class="row justify-content-center">
                <div class="col">
                    <span class="button h5" @click.prevent="validate">
                        <i class="fa-regular fa-square-check text-success" v-tooltip="'Validé'" v-if="episode.valid"></i>
                        <i class="fa-regular fa-square" v-tooltip="'A faire'" v-else></i>
                    </span>
                </div>
                <div class="col text-end">
                    <i class="fa-solid fa-pen fa-fw button me-1" v-tooltip="'Editer'" @click="$emit('on-edit-episode', episode)"></i>
                <i class="fa-solid fa-trash fa-fw button text-danger" v-tooltip="'Supprimer'" @click="deleteModal = true"></i>
                </div>
            </div>
        </footer>
        <Delete :title="episode.title" :loading="loading" v-if="deleteModal" @on-confirm="onDelete" @on-cancel="deleteModal = false"></Delete>
    </article>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import Delete from '&utils/Delete.vue';
import { createToastify } from '&utils/toastify.js';

export default {
    name: 'EpisodeCard',

    components: {
        Delete,
    },

    emits: ['on-edit-episode'],

    props: {
        episode: Object,
    },

    data() {
        return {
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
            const status = await this.synopsisStore.deleteEpisode(this.episode.id, this.episode.chapterId);
            if (!status) {
                createToastify("La suppression a échoué.", 'error');
            }
            this.loading = false;
        },

        validate() {
            
        }
    },
}
</script>

<style scoped>
.button {
    cursor: pointer;
}
</style>