<template>
    <article>
        <h1 class="display-5 mb-5">Articles</h1>
        <div class="row align-items-center mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <label for="searchValue">Recherche</label>
                    </span>
                    <input type="text" class="form-control w-auto input-sm" id="searchValue" v-model="searchValue" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <span class="input-group-text">
                        <label for="searchField">Champ</label>
                    </span>
                    <select v-model="searchField" id="searchField" class="form-control w-auto input-sm">
                        <option value="title">Titre</option>
                        <option value="categoryName">Catégorie</option>
                    </select>
                </div>
            </div>
        </div>

        <DataTable :items="articleStore.articles" 
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
            <template #item-link="{ link }">
                <a :href="link" target="_blank">{{ link }}</a>
            </template>
            <template #item-category="{ category }">
                <span class="badge bg-secondary">
                    <i :class="'fa-solid ' + category.icon + ' fa-fw'"></i> {{ category.title }}
                </span>
            </template>
            <template #item-parents="{ parents }">
                <div v-for="parent in parents">
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
    </article>
    <ArticleModal :data="article"  @on-close="showEditModal = false" v-if="showEditModal"></ArticleModal>
    <Delete :title="article.title" v-if="showDeleteModal" @on-confirm="deleteAction" @on-close="showDeleteModal = false"></Delete>
</template>

<script>
import { mapStores } from "pinia";
import { useArticleStore } from '&synopsis/stores/article.js';
import { createToastify } from '&utils/toastify.js';
import Delete from '&utils/Delete.vue';
import ArticleModal from '&synopsis/components/synopsis_article/ArticleModal.vue';
import { useArticleCategoryStore } from '&synopsis/stores/articleCategory.js';
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';

export default {
    name: 'ArticleIndex',

    components: {
        DataTable: Vue3EasyDataTable,
        Delete,
        ArticleModal
    },

    data() {
        return {
            headers: [ 
                {text: 'Titre', value: 'title', sortable: true},
                {text: 'Catégorie', value: 'category', sortable: true},
                {text: 'Lien', value: 'link', sortable: true},
                {text: 'Synopsis', value: 'parents', sortable: true},
                {text: "Actions", value: "operation"},
            ],
            character: {id: null, title: null, link: null, category: null, content: ''},
            showEditModal: false,
            showDeleteModal: false,
            searchField: 'title',
            searchValue: ''
        }
    },

    computed: {
        ...mapStores(useArticleStore, useArticleCategoryStore),
    },

    async mounted () {
        const status = await this.articleStore.getAll();
        if (!status) {
            createToastify('Le chargement a échoué', 'error');
        }

        const statusCategory = await this.articleCategoryStore.getCategories();
        if (!statusCategory) {
            createToastify('Le chargement des catégories a échoué', 'error');
        }
    },

    methods: {
        edit(data) {
            this.article = data;
            this.showEditModal = true;
        },

        openDeleteModal(data) {
            this.article = data;
            this.showDeleteModal = true;
        },

        async deleteAction() {
            this.showDeleteModal = false;
            const status = await this.articleStore.delete(this.article.id);
            if (status) {
                createToastify("L'article a été supprimé", 'success');
            }

            this.character = {id: null, title: null, link: null, category: null, content: ''};
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
    --easy-table-footer-font-size: 12px;
}
</style>
