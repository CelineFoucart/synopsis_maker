<template>
    <article class="card h-100 position-relative" :class="{'card-width-lg' : episode.color}" :style="{'border-color': episode.color ? episode.color : 'rgba(0, 0, 0, 0.176' }">
        <div class="card-body">
            <h3 class="card-title h5 fw-bold" :class="{'text-success': episode.valid }">
                <span class="handle" v-if="archived === false"><i class="fa-solid fa-fw fa-arrows-up-down-left-right"></i></span>
                {{ episode.title }}
                <i class="fa-solid fa-box-archive fa-fw button" v-tooltip="'Archivé'" v-if="episode.archived === true"></i>
            </h3>
            <p v-if="episode.characters.length > 0" class="mb-0">
                <i class="fa-solid fa-user fa-fw"></i>
                <CharacterBadge :character="character" v-for="character in episode.characters" :key="character.id"></CharacterBadge>
            </p>
            <p v-if="episode.places.length > 0" class="mb-0">
                <i class="fa-solid fa-location-dot fa-fw"></i>
                <PlaceBadge :place="place" v-for="place in episode.places" :key="place.id"></PlaceBadge>
            </p>
            <p class="mt-2" style="white-space: pre-wrap;" :class="{'text-success': episode.valid }" v-if="showDescription">
                {{ substract(episode.description) }}
            </p>
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
                    <i class="fa-solid fa-box-archive fa-fw button" v-tooltip="'Archiver'" @click="$emit('on-archive-episode', episode)" v-if="!episode.chapterArchived"></i>
                    <i class="fa-solid fa-pen fa-fw button" v-tooltip="'Editer'" @click="$emit('on-edit-episode', episode)" v-if="!archived"></i>
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
import CharacterBadge from '&synopsis/components/character/CharacterBadge.vue';
import PlaceBadge from '&synopsis/components/place/PlaceBadge.vue';

export default {
    name: 'EpisodeCard',

    components: {
        Delete,
        CharacterBadge,
        PlaceBadge
    },

    emits: ['on-edit-episode', 'on-archive-episode'],

    props: {
        episode: Object,
        archived: {
            type: Boolean,
            default: false
        },
        showDescription: Boolean,
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
        substract(value) {
            if (value === null) {
                return '';
            }

            const MAX_LENGTH = 200;

            if (value.length <= MAX_LENGTH) {
                return value;
            }

            return value.substring(0, MAX_LENGTH) + '[...]';
        },

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

.card-width-lg {
    border-left-width: 8px;
}
</style>