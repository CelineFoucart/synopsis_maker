<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="articleModal" tabindex="-1" aria-labelledby="articleModalLabel">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5" id="articleModalLabel">Article</h3>
                        <button type="button" class="btn-close" aria-label="fermeture" @click.prevent="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label required">Titre</label>
                                    <input type="text" class="form-control" id="title" v-model="title" :class="{ 'is-invalid': v$.title.$errors.length }">
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire et doit faire entre 2 et 255 caractères.
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="category" class="form-label">Catégorie</label>
                                    <select class="form-select" v-model="category" id="category"></select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label mb-0">Contenu</label>
                            <Description v-model:data="content" :editMode="true"></Description>
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">Lien</label>
                            <input type="url" class="form-control" id="link" v-model="link" :class="{ 'is-invalid': v$.link.$errors.length }">
                            <div class="form-text">Ajoutez un lien vers un article ou une page de blog sur cet élément.</div>
                            <div class="invalid-feedback">
                                Ce champ doit faire entre 10 et 255 caractères et est être une URL valide.
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" tabindex="-1" @click.prevent="closeModal">
                            <i class="fa-solid fa-xmark"></i>
                            Annuler
                        </button>
                        <button type="button" class="btn btn-success btn-sm" @click.prevent="save">
                            <i class="fa-solid fa-spinner fa-spin" v-if="loading"></i>
                            <i class="fa-solid fa-floppy-disk" v-else></i>
                            Sauvegarder
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="js">
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { useArticleStore } from '&synopsis/stores/article.js';
import { useArticleCategoryStore } from '&synopsis/stores/articleCategory.js';
import { createToastify } from '&utils/toastify.js';
import { required, maxLength, minLength, url  } from '@vuelidate/validators';
import { useVuelidate } from '@vuelidate/core';
import Description from '&utils/Description.vue';
import 'choices.js/public/assets/styles/choices.css';
import Choices from 'choices.js';

export default {
    name: 'ArticleModal',

    components: {
        Description,
    },

    props: {
        data: Object
    },

    data() {
        return {
            title: null,
            category: null,
            link: null,
            content: '',
            v$: useVuelidate(),
            loading: false,
        }
    },

    computed: {
        ...mapStores(useSynopsisStore, useArticleStore, useArticleCategoryStore),
    },

    validations () {
        return {
            title: { required, maxLength: maxLength(255), minLength: minLength(2) },
            link: { maxLength: maxLength(255), minLength: minLength(10), url },
            category: { required },
            content: { required },
        }
    },

    mounted () {
        this.title = this.data.title;
        this.category = this.data.id === null ? this.data.category : this.data.category.id;
        this.content = this.data.content;
        this.link = this.data.link;

        const choice = new Choices('#category', {
            allowHTML: true,
            loadingText: 'Chargement...',
            removeItemButton: true,
            noResultsText: 'Aucun résultat',
            noChoicesText: 'Aucun élément',
            itemSelectText: 'Cliquer pour sélectionner',
        });

        const options = [];
        this.articleCategoryStore.categories.forEach(element => {
            const isSelected = this.category == element.id;
            options.push({
                value: element.id, 
                label: `<i class="${element.icon} fa-fw"></i> ${element.title}`, 
                selected: isSelected
            });
        });
        choice.clearChoices();
        choice.setChoices(options);
    },

    methods: {
        closeModal() {
            this.$emit('on-close');
        },

        async save() {
            this.loading = true;
            const result = await this.v$.$validate();

            if (!result) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
                this.loading = false;
                return;
            }

            const data = {
                title: this.title, 
                link: this.link,
                category: this.category, 
                content: this.content,
            };

            let success = false;
            if (this.data.id) {
                success = await this.articleStore.edit(this.data.id, data);
            } else {
                success = await this.synopsisStore.addArticle(data);
            }

            if (!success) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
                this.loading = false;
            } else {
                this.$emit('on-refresh');
                this.closeModal();
            }
        },
    },
}
</script>

<style scoped>
#articleModal {
    display: block;
}

#title {
    height: 46px;
    border-radius: 2.5px;
}
</style>