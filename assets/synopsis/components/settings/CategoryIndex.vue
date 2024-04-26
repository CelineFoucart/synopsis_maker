<template>
    <section>
        <h2 class="mb-5 mt-3 fw-normal">Catégories de synopsis</h2>

        <div class="row align-items-center">
            <div class="col-8">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="input-group">
                        <span class="input-group-text">
                            <label for="searchValue">Recherche</label>
                        </span>
                        <input type="text" class="form-control w-auto input-sm" id="searchValue" v-model="searchValue" />
                    </div>

                    <div class="input-group">
                        <span class="input-group-text">
                            <label for="searchField">Champ</label>
                        </span>
                        <select v-model="searchField" id="searchField" class="form-control w-auto input-sm">
                            <option value="title">Titre</option>
                            <option value="description">Description</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-4 text-end">
                <button type="button" class="btn btn-success btn-sm" v-tooltip="'Ajouter'" @click="appendCategory">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </div>

        <DataTable :items="categoryStore.categories" 
            :headers="headers" 
            :rows-items="[10, 25, 50, 100]"
            empty-message="Aucun résultat"
            rows-per-page-message="Elément par page"
            rows-of-page-separator-message="sur"
            :search-field="searchField"
            :search-value="searchValue"
            :buttons-pagination="true"
            show-index
            theme-color="#0d6efd"
            sort-by="title"
            alternating>
            <template #item-description="{ description }">
                <div style="white-space: pre-wrap;">{{description}}</div>
            </template>
            <template #item-operation="item">
                <div class="btn-group btn-group-sm">
                    <button type="button" class="btn btn-primary" v-tooltip="'Editer'" @click="editCategory(item)">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>   
                    <button type="button" class="btn btn-danger" v-tooltip="'Supprimer'" @click="openDeleteModal(item)">
                        <i class="fa-solid fa-trash"></i>
                    </button>   
                </div>
            </template>
        </DataTable>
        <CategoryModal :category="category" v-if="showEditModal" @on-close="showEditModal = false"></CategoryModal>
        <Delete :loading="loading" :title="category.title" v-if="showDeleteModal" @on-confirm="deleteCategory" @on-close="showDeleteModal = false"></Delete>
    </section>
</template>

<script>
import { mapStores } from "pinia";
import { useCategoryStore } from '&synopsis/stores/category.js';
import { createToastify } from '&utils/toastify.js';
import CategoryModal from '&synopsis/components/settings/CategoryModal.vue';
import Delete from '&utils/Delete.vue';
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';

export default {
    name: 'CategoryIndex',

    components: {
        DataTable: Vue3EasyDataTable,
        CategoryModal,
        Delete
    },

    data() {
        return {
            loading: false,
            headers: [ 
                {text: 'Titre', value: 'title', sortable: true},
                {text: 'Description', value: 'description', sortable: true},
                {text: "Actions", value: "operation"},
            ],
            category: {id: null, title: null, description: null},
            showEditModal: false,
            showDeleteModal: false,
            searchField: 'title',
            searchValue: ''
        }
    },

    computed: {
        ...mapStores(useCategoryStore),
    },

    async mounted () {
        const status = await this.categoryStore.getCategories();
        if (!status) {
            createToastify('Le chargement a échoué', 'error');
        }
    },

    methods: {
        appendCategory() {
            this.category = {id: null, title: null, description: null};
            this.showEditModal = true;
        },

        editCategory(data) {
            this.category = {id: data.id, title: data.title, description: data.description};
            this.showEditModal = true;
        },

        openDeleteModal(data) {
            this.category = {id: data.id, title: data.title, description: data.description};
            this.showDeleteModal = true;
        },

        async deleteCategory() {
            this.loading = true;
            const status = await this.categoryStore.deleteCategory(this.category.id);
            if (status) {
                createToastify('La catégorie a été supprimée', 'success');
            }
            
            this.showDeleteModal = false;
            this.category = {id: null, title: null, description: null};
            this.loading = false;
        }
    },
}
</script>

<style>
:root {
    --easy-table-header-font-size: 16px;
    --easy-table-body-row-font-size: 16px;
    --easy-table-header-item-padding: 0.5rem 0.5rem;
    --easy-table-body-item-padding: 0.5rem 0.5rem;
    --easy-table-footer-padding: 0.5rem 0.5rem;
}
</style>
