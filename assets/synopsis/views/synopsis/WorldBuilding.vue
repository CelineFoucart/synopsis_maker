<template>
    <Error v-if="error"></Error>
    <article v-if="synopsisStore.synopsis !== null">
        <HeaderSynopsis :synopsis="synopsisStore.synopsis"></HeaderSynopsis>

        <div class="row mt-4">
            <div class="col-md-8 col-lg-9">
                <article v-if="(homepage === null || homepage.length < 1) && selectedArticle === null" class="text-center">
                    <h2 class="mb-3 ">Bienvenue dans le concepteur d'univers de ce projet</h2>
                    <p class="h5 text-muted mb-4">
                        Gérez les articles qui présentent l'univers du projet et le worldbuilding. Depuis la page 
                        configuration, ajoutez des catégories d'articles.
                    </p>
                    <button type="button" class="btn btn-success btn-sm">Modifier le contenu de la page d'accueil</button>
                </article>
                <div v-else-if="homepage !== null && homepage.length > 0 && selectedArticle === null">
                    <div v-html="homepage"></div>
                </div>
                <article v-else>
                    <header class="border-bottom">
                        <h2>
                            <a :href="selectedArticle.link" v-if="selectedArticle.link !== null" class="text-decoration-none">
                                {{ selectedArticle.title }}
                                <i class="fa-solid fa-arrow-up-right-from-square fa-fw"></i>
                            </a>
                            <span v-else>{{ selectedArticle.title }}</span>
                        </h2>
                    </header>
                    <div class="my-2 d-flex align-items-center justify-content-between">
                        <span class="badge bg-secondary">
                            <i :class="'fa-solid ' + selectedArticle.category.icon + ' fa-fw'"></i> {{ selectedArticle.category.title }}
                        </span>
                        <div class="btn-group">
                            <button class="btn btn-dark btn-sm" v-tooltip="'éditer'" @click="openEditModal">
                                <i class="fa-solid fa-pen fa-fw"></i>
                                <span class="visually-hidden">éditer</span> 
                            </button>
                            <button class="btn btn-danger btn-sm" v-tooltip="'retirer'" @click="unlinkModal = true">
                                <i class="fa-solid fa-link-slash fa-fw"></i>
                                <span class="visually-hidden">retirer du synopsis</span> 
                            </button>
                        </div>
                    </div>
                    <div v-html="selectedArticle.content"></div>
                    <ArticleModal :data="selectedArticle" @on-refresh="onRefresh" @on-close="ediModal = false" v-if="ediModal"></ArticleModal>
                    <UnlinkModal 
                        :loading="loading" 
                        :title="selectedArticle.title" 
                        @on-confirm="unlinkElement" 
                        @on-close="unlinkModal = false" 
                        v-if="unlinkModal">
                    </UnlinkModal>
                </article>
            </div>
            <div class="col-md-4 col-lg-3">
                <Summary @on-select="onSelect"></Summary>
            </div>
        </div>
    </article>
</template>

<script lang="js">
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { useArticleCategoryStore } from '&synopsis/stores/articleCategory.js';
import { useArticleStore } from '&synopsis/stores/article.js';
import { createToastify } from '&utils/toastify.js';
import Error from '&utils/Error.vue';
import HeaderSynopsis from '&synopsis/components/synopsis_show/HeaderSynopsis.vue';
import Summary from '&synopsis/components/synopsis_article/Summary.vue';
import ArticleModal from '&synopsis/components/synopsis_article/ArticleModal.vue';
import UnlinkModal from '&utils/UnlinkModal.vue';

export default {
    name: 'WorldBuilding',

    components: {
        HeaderSynopsis,
        Error,
        Summary,
        ArticleModal,
        UnlinkModal
    },

    data() {
        return {
            error: false,
            selectedArticle: null,
            ediModal: false,
            unlinkModal: false,
            loading: false
        }
    },

    computed: {
        ...mapStores(useSynopsisStore, useArticleCategoryStore, useArticleStore),

        homepage() {
            return this.synopsisStore.synopsis ? this.synopsisStore.synopsis.worldbuildingHome : null;
        }
    },

    async mounted () {
        if (this.synopsisStore.synopsis !== null) {
            return;
        }
        
        const status = await this.synopsisStore.getSynopsis(this.$route.params);
        if (!status) {
            createToastify("Ce synopsis n'existe pas.", 'error');
            this.error = true;
        }

        const statusCategory = await this.articleCategoryStore.getCategories();
        if (!statusCategory) {
            createToastify('Le chargement des catégories a échoué', 'error');
        }
    },

    methods: {
        onSelect(article) {
            this.selectedArticle = article;
        },

        onRefresh() {
            for (let i = 0; i < this.synopsisStore.synopsis.articles.length; i++) {
                const article = this.synopsisStore.synopsis.articles[i];

                if (article.id === this.selectedArticle.id) {
                    this.selectedArticle = article;
                    break;
                }
            }
        },

        openEditModal() {
            if (this.selectedArticle === null) {
                return;
            }

            this.articleStore.setArticles(this.synopsisStore.synopsis.articles);
            this.ediModal = true;
        },

        async unlinkElement() {
            this.loading = true;
            let success = false;
            if (this.selectedArticle !== null) {
                success = await this.synopsisStore.removeArticle(this.selectedArticle.id);
            }

            if (success) {
                createToastify("L'élément a été retiré du synopsis.", 'success');
                this.selectedArticle = null;
                this.articleStore.setArticles(this.synopsisStore.synopsis.articles);
            } else {
                createToastify("L'opération a échoué.", 'error');
            }

            this.unlinkModal = false;
            this.loading = false;
        }
    },
}
</script>
