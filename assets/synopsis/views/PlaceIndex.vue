<template>
    <article>
        <h1 class="display-5 mb-5">Lieux</h1>
        <div class="row align-items-center mb-3">
            <div class="col-lg-6 text-end">
                <div class="input-group">
                    <span class="input-group-text">
                        <label for="searchValue">Recherche</label>
                    </span>
                    <input type="text" class="form-control w-auto input-sm" id="searchValue" v-model="searchValue" />
                </div>
            </div>
        </div>

        <DataTable :items="placeStore.places" 
            :headers="headers" 
            :rows-items="[10, 25, 50, 100]"
            empty-message="Aucun résultat"
            rows-per-page-message="Elément par page"
            rows-of-page-separator-message="sur"
            search-field="title"
            :search-value="searchValue"
            :buttons-pagination="true"
            show-index
            theme-color="#0d6efd"
            sort-by="title"
            alternating>
            <template #item-link="{ link }">
                <a :href="link" target="_blank">{{ link }}</a>
            </template>
            <template #item-relations="{ relations }">
                <div v-for="parent in relations">
                    <router-link :to="{ name: 'SynopsisShow', params:{slug: parent.slug, id: parent.id} }">
                        {{ parent.title }}
                    </router-link>
                </div>
            </template>
            <template #item-operation="item">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-primary" v-tooltip="'Editer'" @click="edit(item)">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>   
                    <button type="button" class="btn btn-danger" v-tooltip="'Supprimer'" @click="openDeleteModal(item)">
                        <i class="fa-solid fa-trash"></i>
                    </button>   
                </div>
            </template>
        </DataTable>
        <Loading v-if="loading"></Loading>
        <PlaceModal :data="place" v-if="showEditModal" @on-close="showEditModal = false"></PlaceModal>
        <Delete :title="place.title" v-if="showDeleteModal" @on-confirm="deleteAction" @on-close="showDeleteModal = false"></Delete>
    </article>
</template>

<script>
import { mapStores } from "pinia";
import { usePlaceStore } from '&synopsis/stores/place.js';
import Loading from '&utils/Loading.vue';
import { createToastify } from '&utils/toastify.js';
import PlaceModal from '&synopsis/components/place/PlaceModal.vue';
import Delete from '&utils/Delete.vue';
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';

export default {
    name: 'PlaceIndex',

    components: {
        DataTable: Vue3EasyDataTable,
        Loading,
        PlaceModal,
        Delete
    },

    data() {
        return {
            loading: true,
            headers: [ 
                {text: 'Titre', value: 'title', sortable: true},
                {text: 'Lien', value: 'link', sortable: true},
                {text: 'Synopsis', value: 'relations', sortable: true},
                {text: "Actions", value: "operation"},
            ],
            place: {id: null, title: null, role: null, link: null, description: null},
            showEditModal: false,
            showDeleteModal: false,
            searchValue: ''
        }
    },

    computed: {
        ...mapStores(usePlaceStore),
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
        edit(data) {
            this.place = data;
            this.showEditModal = true;
        },

        openDeleteModal(data) {
            this.place = data;
            this.showDeleteModal = true;

        },

        async deleteAction() {
            this.loading = true;
            this.showDeleteModal = false;
            const status = await this.placeStore.delete(this.place.id);
            if (status) {
                createToastify('La catégorie a été supprimée', 'success');
            }

            this.place = {id: null, title: null, role: null, link: null, description: null};
            this.loading = false;
        }
    },
}
</script>

