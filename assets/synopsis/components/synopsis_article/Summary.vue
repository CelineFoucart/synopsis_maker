<template>
    <aside class="card">
        <div class="card-header">
            <div class="row g-2 align-items-center">
                <div class="col-8">
                    <h2 class="h5 mb-0 fw-bold">
                        Sommaire
                        <span class="badge rounded-pill text-bg-secondary" v-tooltip="'nombre d\'article : ' + synopsisStore.synopsis.articles.length">
                            {{ synopsisStore.synopsis.articles.length }}
                        </span>
                    </h2>
                </div>
                <div class="col-4 text-end">
                    <button type="button" class="btn btn-sm btn-success me-1" v-tooltip="'Ajouter depuis la liste'" @click="listModal = true">
                        <i class="fa-solid fa-list fa-fw"></i>
                    </button>
                    <button type="button" class="btn btn-success btn-sm" v-tooltip="'Ajouter'" @click="openCreateModal(null)">
                        <i class="fa-solid fa-plus fa-fw"></i>
                        <span class="visually-hidden">Ajouter</span>
                    </button>
                </div>
                <div class="col-12">
                    <input v-model="query" class="form-control form-control-sm" type="text" placeholder="filtrer les articles..." aria-label="filtrer">
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-2 fw-bold" role="button" @click="onSelect(null)">
                <i class="fas fa-home fa-fw"></i> Page d'accueil
            </div>
            <CategoryRow 
                :category="row.category" 
                :articles="row.articles" 
                :key="row.category.id"
                @on-select="onSelect"
                v-for="row in categories">
            </CategoryRow>
        </div>
    </aside>
    <ArticleModal :data="article" @on-close="showAddModal = false" v-if="showAddModal"></ArticleModal>
    <ArticleListModal @on-close="listModal = false" v-if="listModal"></ArticleListModal>
</template>

<script lang="js">
import { mapStores } from "pinia";
import { useArticleCategoryStore } from '&synopsis/stores/articleCategory.js';
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import ArticleModal from '&synopsis/components/synopsis_article/ArticleModal.vue';
import CategoryRow from '&synopsis/components/synopsis_article/CategoryRow.vue';
import ArticleListModal from '&synopsis/components/synopsis_article/ArticleListModal.vue';

export default {
    name: 'Summary',

    components: {
        ArticleModal,
        CategoryRow,
        ArticleListModal
    },

    emits: ['on-select'],

    data() {
        return {
            showAddModal: false,
            listModal: false,
            article: { title: null},
            query: null
        }
    },

    computed: {
        ...mapStores(useArticleCategoryStore, useSynopsisStore),

        categories() {
            const categories = {};

            if (  this.synopsisStore.synopsis === null) {
                return categories;
            }

            for (let i = 0; i < this.synopsisStore.synopsis.articles.length; i++) {
                const article = this.synopsisStore.synopsis.articles[i];

                if (this.query !== null && this.query.length > 2) {
                    if (article.title.toLowerCase().indexOf(this.query.toLowerCase()) === -1) {
                        continue;
                    }
                }

                if (article.category.id in categories) {
                    categories[article.category.id].articles.push(article);
                } else {
                    categories[article.category.id] = {
                        category: article.category,
                        articles: [article]
                    }
                }
            }

            return categories;
        }
    },

    mounted () {
        this.resetArticle();
    },

    methods: {
        onSelect(article) {
            this.$emit('on-select', article);
        },

        resetArticle() {
            this.article = {id: null, title: null, link: null, category: null, content: ''};
        },

        openCreateModal() {
            this.resetArticle();
            this.showAddModal = true;
        }
    },
}
</script>