<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="articleListeModal" tabindex="-1" aria-labelledby="articleListeModalLabel">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5" id="articleListeModalLabel">Liste des articles</h3>
                        <button type="button" class="btn-close" aria-label="fermeture" @click.prevent="closeModal"></button>
                    </div>
                    <div class="modal-body position-relative">
                        <div class="row">
                            <div class="col-6 fw-bold">
                                Ajouter un article
                            </div>
                            <div class="col-6">
                                <div class="input-group input-group-sm mb-3">
                                    <span class="input-group-text"><label for="search">Filtrer</label></span>
                                    <input v-model="search" type="text" class="form-control" id="search">
                                </div>
                            </div>
                        </div>
                        <div class="row g-2 pb-2 border" :class="{'scrollable-container': articles.length > 12}">
                            <div class="col-6" v-for="article in articles">
                                <section class="border bg-light p-1 button" @click="append(article.id)">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="badge bg-secondary">
                                                <i :class="'fa-solid ' + article.category.icon + ' fa-fw'"></i> {{ article.category.title }}
                                            </span>
                                            <h4 class="fs-6 mb-0">{{ article.title }}</h4>
                                        </div>
                                        
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
import { useArticleStore } from '&synopsis/stores/article.js';
import { createToastify } from '&utils/toastify.js';
import Loading from '&utils/Loading.vue';

export default {
    name: 'ArticleListModal',

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
        ...mapStores(useSynopsisStore, useArticleStore),

        articles() {
            const articles = [];

            for (let i = 0; i < this.articleStore.articles.length; i++) {
                const article = this.articleStore.articles[i];

                const index = this.synopsisStore.synopsis.articles.findIndex(element => element.id === article.id);
                if (index !== -1) {
                    continue;
                }

                if (this.search !== null && this.search.length > 2) {
                    if (article.title.toLowerCase().indexOf(this.search.toLowerCase()) === -1) {
                        continue;
                    }
                }

                articles.push(article);
            }

            return articles;
        }
    },

    async mounted () {
        this.loading = true;

        const status = await this.articleStore.getAll();
        if (!status) {
            createToastify('Le chargement a échoué', 'error');
        }
        
        this.loading = false;
    },

    methods: {
        closeModal() {
            this.$emit('on-close');
        },

        async append(articleId) {
            this.loading = true;

            const status = await this.synopsisStore.appendArticleFromList(articleId);
            if (!status) {
                createToastify("L'ajout a échoué.", 'error');
            } else {
                createToastify("L'article a été ajouté.", 'success');
            }

            this.loading = false;
        },
    },
}
</script>

<style scoped>
#articleListeModal {
    display: block;
    z-index: 3000;
}

.modal-body {
    min-height: 350px;
}
</style>