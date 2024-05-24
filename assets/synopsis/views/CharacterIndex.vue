<template>
    <article>
        <h1 class="display-5 mb-5">Personnages</h1>
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

        <DataTable :items="characterStore.characters" 
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
        <CharacterModal :data="character" v-if="showEditModal" @on-close="showEditModal = false"></CharacterModal>
        <Delete :title="character.name" v-if="showDeleteModal" @on-confirm="deleteAction" @on-close="showDeleteModal = false"></Delete>
    </article>
</template>

<script>
import { mapStores } from "pinia";
import { useCharacterStore } from '&synopsis/stores/character.js';
import Loading from '&utils/Loading.vue';
import { createToastify } from '&utils/toastify.js';
import CharacterModal from '&synopsis/components/character/CharacterModal.vue';
import Delete from '&utils/Delete.vue';
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';

export default {
    name: 'CharacterIndex',

    components: {
        DataTable: Vue3EasyDataTable,
        Loading,
        CharacterModal,
        Delete
    },

    data() {
        return {
            loading: true,
            headers: [ 
                {text: 'Nom', value: 'name', sortable: true},
                {text: 'Lien', value: 'link', sortable: true},
                {text: 'Synopsis', value: 'relations', sortable: true},
                {text: "Actions", value: "operation"},
            ],
            character: {id: null, name: null, description: null, link: null, biography: '', appearance: '', personality: []},
            showEditModal: false,
            showDeleteModal: false,
            searchValue: ''
        }
    },

    computed: {
        ...mapStores(useCharacterStore),
    },

    async mounted () {
        this.loading = true;

        const status = await this.characterStore.getAll();
        if (!status) {
            createToastify('Le chargement a échoué', 'error');
        }
        
        this.loading = false;
    },

    methods: {
        edit(data) {
            this.character = data;
            this.showEditModal = true;
        },

        openDeleteModal(data) {
            this.character = data;
            this.showDeleteModal = true;
        },

        async deleteAction() {
            this.loading = true;
            this.showDeleteModal = false;
            const status = await this.characterStore.delete(this.character.id);
            if (status) {
                createToastify('La catégorie a été supprimée', 'success');
            }

            this.character = {id: null, name: null, description: null, link: null, biography: '', appearance: '', personality: []};
            this.loading = false;
        }
    },
}
</script>
