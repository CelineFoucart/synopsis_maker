<template>
    <article class="card h-100" :style="{'border-color': episode.color ? episode.color : 'rgba(0, 0, 0, 0.176' }">
        <div class="card-body">
            <h3 class="card-title h5 fw-bold" :class="{'text-success': episode.valid }">
                <span class="handle"><i class="fa-solid fa-fw fa-arrows-up-down-left-right"></i></span>
                {{ episode.title }}
            </h3>
            <p style="white-space: pre-wrap;" :class="{'text-success': episode.valid }">{{ episode.description }}</p>
        </div>
        <footer class="card-footer fs-5">
            <div class="row m-0">
                <div class="col p-0">
                    <span class="button" @click.prevent="validate" v-if="!loading">
                        <i class="fa-solid fa-circle-check text-success fa-fw" v-tooltip="'Validé'" v-if="episode.valid"></i>
                        <i class="fa-solid fa-circle-xmark text-danger fa-fw" v-tooltip="'Non validé'" v-else></i>
                    </span>
                    <span v-else>
                        <i class="fa-solid fa-spinner fa-spin fa-fw"></i>
                    </span>
                </div>
                <div class="col p-0 d-flex justify-content-end gap-1 align-items-center">
                    <i class="fa-solid fa-pen fa-fw button" v-tooltip="'Editer'" @click="$emit('on-edit-episode', episode)"></i>
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

        async validate() {
            this.loading = true;
            const status = await this.synopsisStore.validateEpisode(this.episode.id, this.episode.chapterId);
            if (!status) {
                createToastify("Le changement de statut de validation a échoué.", 'error');
            }
            this.loading = false;
        },
    },
}
</script>

<style scoped>
.button {
    cursor: pointer;
}
</style>