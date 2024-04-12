<template>
    <section class="card mt-4">
        <header class="card-header">
            <div class="row">
                <div class="col-9"><h2 class="h5 mb-0 card-title">Lieux associés</h2></div>
                <div class="col-3 text-end">
                    <button type="button" class="btn btn-sm btn-success me-1" v-tooltip="'Ajouter depuis la liste'" @click="placeListModalShow = true">
                        <i class="fa-solid fa-list fa-fw"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-success" v-tooltip="'Ajouter'" @click="openPlaceModal(null)">
                        <i class="fa-solid fa-plus fa-fw"></i>
                    </button>
                </div>
            </div>
        </header>
        <div class="card-body">
            <table class="table border mb-0 table-striped">
                <thead>
                    <tr>
                        <th>Lieu</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="place in synopsisStore.synopsis.places" :key="place.id">
                        <td>
                            <h3 class="fw-bold h6 mb-0">
                                <a :href="place.link" v-if="place.link !== null" target="_blank" class="text-decoration-none">
                                    {{ place.title }}
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                                <span v-else>{{ place.title }}</span>
                            </h3>
                            <div style="white-space: pre-wrap;">{{ substract(place.role) }}</div>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-dark" v-tooltip="'Editer'" @click="openPlaceModal(place)">
                                    <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                    <span class="visually-hidden">Editer</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" v-tooltip="'Retirer'" @click="openUnlinkModal(place)">
                                    <i class="fa-solid fa-link-slash fa-fw"></i>
                                    <span class="visually-hidden">Retirer</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="synopsisStore.synopsis.places.length === 0" class="text-center">
                        <td colspan="3">Aucun lieu associé</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <PlaceListModal @on-close="placeListModalShow = false" v-if="placeListModalShow"></PlaceListModal>
        <PlaceModal :data="place" @on-refresh="onRefresh" @on-close="placeModalShow = false" v-if="placeModalShow"></PlaceModal>
        <UnlinkModal :loading="loading" :title="place.title" @on-confirm="unlinkElement" @on-close="unlinkModalShow = false" v-if="unlinkModalShow"></UnlinkModal>
    </section>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { usePlaceStore } from '&synopsis/stores/place.js';
import PlaceModal from '&synopsis/components/place/PlaceModal.vue';
import PlaceListModal from '&synopsis/components/place/PlaceListModal.vue';
import UnlinkModal from '&utils/UnlinkModal.vue';
import { createToastify } from '&utils/toastify.js';

export default {
    name: 'SynopsisPlace',

    components: {
        PlaceModal,
        UnlinkModal,
        PlaceListModal
    },

    data() {
        return {
            placeModalShow: false,
            unlinkModalShow: false,
            placeListModalShow: false,
            place: { title: null},
            loading: false
        }
    },

    computed: {
        ...mapStores(useSynopsisStore, usePlaceStore),
    },

    mounted () {
        this.resetPlace();
        this.placeStore.setPlaces(this.synopsisStore.synopsis.places);
    },

    methods: {
        resetPlace() {
            this.place = {id: null, title: null, link: null, role: null, description: ''};
        },

        openPlaceModal(place = null) {
            if (place === null) {
                this.resetPlace();
            } else {
                this.place = place;
            }

            this.placeModalShow = true;
        },

        openUnlinkModal(place) {
            this.place = place;
            this.unlinkModalShow = true;
        },

        substract(value) {
            const MAX_LENGTH = 150;

            if (value.length <= MAX_LENGTH) {
                return value;
            }

            return value.substring(0, MAX_LENGTH) + '[...]';
        },

        onRefresh() {
            this.synopsisStore.refreshSynopsis(this.placeStore.places);
        },

        async unlinkElement() {
            this.loading = true;

            let success = false;
            if (this.place !== null) {
                success = await this.synopsisStore.removePlace(this.place.id);
            }

            if (success) {
                createToastify("L'élément a été retiré du synopsis.", 'success');
                this.placeStore.setPlaces(this.synopsisStore.synopsis.places);
            } else {
                createToastify("L'opération a échoué.", 'error');
            }

            this.unlinkModalShow = false;
            this.loading = false;
        }
    },
}
</script>