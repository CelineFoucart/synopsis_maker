<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="placeListModal" tabindex="-1" aria-labelledby="placeListModalLabel">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5" id="placeListModalLabel">Liste des lieux</h3>
                        <button type="button" class="btn-close" aria-label="fermeture" @click.prevent="closeModal"></button>
                    </div>
                    <div class="modal-body position-relative">
                        <div class="row">
                            <div class="col-6 fw-bold">
                                Ajouter un lieu
                            </div>
                            <div class="col-6">
                                <div class="input-group input-group-sm mb-3">
                                    <span class="input-group-text"><label for="search">Filtrer</label></span>
                                    <input v-model="search" type="text" class="form-control" id="search">
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 pb-2 border" :class="{'scrollable-container': places.length > 12}">
                            <div class="col-6" v-for="place in places">
                                <section class="border bg-light p-1 button" @click="append(place.id)">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4 class="fs-6 mb-0">
                                            {{ place.title }}
                                        </h4>
                                        <span class="fs-6">
                                            <i class="fa-solid fa-plus fa-fw"></i> 
                                        </span>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <Loading v-if="loading"></Loading>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { usePlaceStore } from '&synopsis/stores/place.js';
import { createToastify } from '&utils/toastify.js';
import Loading from '&utils/Loading.vue';

export default {
    name: 'PlaceListModal',

    components: {
        Loading,
    },

    props: {
        data: Object
    },

    data() {
        return {
            loading: false,
            search: null
        }
    },

    computed: {
        ...mapStores(useSynopsisStore, usePlaceStore),

        places() {
            const places = [];

            for (let i = 0; i < this.placeStore.places.length; i++) {
                const place = this.placeStore.places[i];

                const index = this.synopsisStore.synopsis.places.findIndex(element => element.id === place.id);
                if (index !== -1) {
                    continue;
                }

                if (this.search !== null && this.search.length > 2) {
                    if (place.title.toLowerCase().indexOf(this.search.toLowerCase()) === -1) {
                        continue;
                    }
                }

                places.push(place);
            }

            return places;
        }
    },

    async mounted () {
        this.loading = true;

        const status = await this.placeStore.getAll();
        if (!status) {
            createToastify('Le chargement a échoué', 'error');
        }
        
        this.loading = false;
    },

    methods: {
        closeModal() {
            this.$emit('on-close');
        },

        async append(placeId) {
            this.loading = true;

            const status = await this.synopsisStore.appendPlaceFromList(placeId);
            if (!status) {
                createToastify("L'ajout a échoué.", 'error');
            } else {
                createToastify("Le lieu a été ajouté.", 'success');
            }

            this.loading = false;
        },
    },
}
</script>

<style scoped>
#placeListModal {
    display: block;
    z-index: 3000;
}

.modal-body {
    min-height: 350px;
}
</style>