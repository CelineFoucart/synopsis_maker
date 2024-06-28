<template>
    <header>
        <div class="row">
            <div class="col-6 col-md-8">
                <h1 class="display-5">
                    <span class="me-2">{{ synopsis.title }}</span>
                    <span class="action-btn-header text-success h5 me-2" role="button" v-tooltip="'Editer les informations'" @click="editInfoModal = true">
                        <i class="fa-solid fa-pen"></i>
                    </span>
                </h1>
            </div>
            <div class="col-6 col-md-4 text-end">
                <router-link :to="{ name: 'SynopsisIndex' }" class="btn btn-sm btn-dark mb-1" v-tooltip="'Liste'">
                    <i class="fa-solid fa-arrow-left fa-fw"></i>
                    <span class="visually-hidden">Liste</span>
                </router-link>
                <div class="btn-group ms-1 mb-1">
                    <router-link :to="{ name: 'SynopsisShow', params:{slug: synopsis.slug, id: synopsis.id} }" class="btn btn-sm btn-dark" v-tooltip="'Informations'">
                        <i class="fa-solid fa-pen-to-square fa-fw"></i>
                        <span class="visually-hidden">Informations générales</span>
                    </router-link>
                    <router-link :to="{ name: 'SynopsisEpisodes', params:{slug: synopsis.slug, id: synopsis.id} }" class="btn btn-sm btn-dark" v-tooltip="'Scènes'">
                        <i class="fa-solid fa-copy fa-fw"></i>
                        <span class="visually-hidden">Scènes</span>
                    </router-link>
                </div>
                <button class="btn btn-sm btn-dark ms-1 mb-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" v-tooltip="'Menu'">
                    <i class="fa-solid fa-ellipsis"></i>
                    <span class="visually-hidden">Menu</span>
                </button>
            </div>
            <div class="col-12">
                <span v-for="category in synopsis.categories" :key="category.id" class="badge me-1 bg-secondary">
                    {{ category.title }}
                </span>
            </div>
        </div>

        <div class="offcanvas offcanvas-end" data-bs-scroll="true" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
            <div class="offcanvas-header border-bottom">
                <h5 id="offcanvasRightLabel">Menu</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <h6 class="fw-bold small"><i class="fa-solid fa-circle-info fa-fw"></i> A propos de ce synopsis</h6>
                <p class="small" style="margin-left: 20px;">{{ synopsisStore.synopsis.pitch ? synopsisStore.synopsis.pitch : 'Aucun pitch' }}</p>
                <hr class="mb-1">
                <h6 class="text-muted small mb-3">Conception</h6>
                <ul class="list-unstyled" style="margin-left: 20px;">
                    <li class="pb-2">
                        <router-link :to="{ name: 'SynopsisShow', params:{slug: synopsis.slug, id: synopsis.id} }" class="text-decoration-none">
                            <i class="fa-solid fa-pen-to-square fa-fw"></i> Informations générales
                        </router-link>
                    </li>
                    <li class="pb-2">
                        <router-link :to="{ name: 'SynopsisEpisodes', params:{slug: synopsis.slug, id: synopsis.id} }" class="text-decoration-none">
                            <i class="fa-solid fa-copy fa-fw"></i> Scènes
                        </router-link>
                    </li>
                    <li class="pb-2">
                        <router-link :to="{ name: 'WorldBuilding', params:{slug: synopsis.slug, id: synopsis.id} }" class="text-decoration-none">
                            <i class="fa-solid fa-book-open fa-fw"></i> Construction du monde
                        </router-link>
                    </li>
                    <li class="pb-2">
                        <router-link :to="{ name: 'SynopsisTodoList', params:{slug: synopsis.slug, id: synopsis.id} }" class="text-decoration-none">
                            <i class="fa-solid fa-table-list fa-fw"></i> Liste de tâches 
                        </router-link>
                    </li>
                    <li class="pb-2">
                        <router-link :to="{ name: 'SynopsisNotes', params:{slug: synopsis.slug, id: synopsis.id} }" class="text-decoration-none">
                            <i class="fa-solid fa-file-lines fa-fw"></i> Prises de note
                        </router-link>
                    </li>
                </ul>
                <hr class="mb-1">
                <h6 class="text-muted small mb-3">Actions</h6>
                <ul class="list-unstyled" style="margin-left: 20px;">
                    <li class="pb-2">
                        <router-link :to="{ name: 'SynopsisSettings', params: {slug: synopsis.slug, id: synopsis.id} }" class="text-decoration-none">
                            <i class="fa-solid fa-gears fa-fw"></i> Réglages et export
                        </router-link>
                    </li>
                    <li class="pb-2">
                        <a :href="printPdf" class="text-decoration-none" target="_blank">
                            <i class="fa-solid fa-print fa-fw"></i> Générer le pdf
                        </a>
                    </li>
                    <li class="pb-2">
                        <a :href="exportWord" class="text-decoration-none" target="_blank">
                            <i class="fa-solid fa-file-word fa-fw"></i> Exporter le fichier word
                        </a>
                    </li>
                    <li class="pb-2">
                        <a href="#" class="text-danger text-decoration-none fw-bold" @click.prevent="deleteModal = true">
                            <i class="fa-solid fa-trash fa-fw"></i> Suppression
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <AddSynopsisModal :synopsis="synopsis" v-if="editInfoModal" @on-close="editInfoModal = false"></AddSynopsisModal>
        <Delete :loading="loading" :title="synopsis.title" v-if="deleteModal" @on-confirm="deleteSynopsis" @on-cancel="deleteModal = false"></Delete>
    </header>
</template>

<script>
import AddSynopsisModal from '&synopsis/components/synopsis/AddSynopsisModal.vue';
import Delete from '&utils/Delete.vue';
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { useCategoryStore } from '&synopsis/stores/category.js';
import { createToastify } from '&utils/toastify.js';

export default {
    name: 'HeaderSynopsis',

    components: {
        AddSynopsisModal,
        Delete
    },

    props: {
        synopsis: Object,
    },

    data() {
        return {
            editInfoModal: false,
            deleteModal: false,
            loading: false
        }
    },

    computed: {
        ...mapStores(useSynopsisStore, useCategoryStore),

        printPdf() {
            return Routing.generate('app_export_pdf', { id: this.synopsis.id });
        },

        exportWord() {
            return Routing.generate('app_export_word', { id: this.synopsis.id });
        }
    },

    async mounted() {
        await this.categoryStore.getCategories();
    },

    methods: {
        async deleteSynopsis() {
            this.loading = true
            const status = await this.synopsisStore.deleteSynopsis(this.synopsisStore.synopsis.id);
            if (status) {
                createToastify('Le synopsis a été supprimé.', 'success');
                this.$router.push('/synopsis');
            } else {
                createToastify('La suppression a échoué.', 'error');
            }
            this.loading = false;
            this.deleteModal = false;
        }
    },

}
</script>

<style scoped>
.action-btn-header {
    cursor: pointer;
}
</style>
