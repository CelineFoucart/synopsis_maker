<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="synopsisModal" tabindex="-1" aria-labelledby="synopsisModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5" id="synopsisModalConfigLabel">Synopsis</h3>
                        <button type="button" class="btn-close" aria-label="fermeture" @click.prevent="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label required">Titre</label>
                            <input type="text" class="form-control" id="title" v-model="title" :class="{ 'is-invalid': v$.title.$errors.length }">
                            <div class="invalid-feedback">
                                Ce champ est obligatoire et doit faire entre 2 et 255 caractères.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="categories" class="form-label required">Catégories</label>
                            <select id="categories" v-model="categories" multiple></select>
                            <div class="form-text">
                                Pas de catégorie ? Ajoutez-en sur la page 
                                <router-link :to="{ name: 'Settings' }">configuration</router-link>.
                            </div>
                            <div class="text-danger small" v-if="v$.categories.$errors.length">
                                Ce champ est obligatoire. Veuillez choisir au moins une catégorie.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Pitch</label>
                            <textarea class="form-control" id="description" rows="3" v-model="pitch" :class="{ 'is-invalid': v$.pitch.$errors.length }"></textarea>
                            <div class="form-text">Définissez un court résumé du synopsis de moins de 2500 caractères.</div>
                            <div class="invalid-feedback">
                                Ce champ doit faire entre 3 et 2500 caractères.
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

<script>
import { mapStores } from "pinia";
import { useCategoryStore } from '&synopsis/stores/category.js';
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { useVuelidate } from '@vuelidate/core'
import { required, maxLength, minLength  } from '@vuelidate/validators';
import { createToastify } from '&utils/toastify.js';
import 'choices.js/public/assets/styles/choices.css';
import Choices from 'choices.js';

export default {
    name: 'AddSynopsisModal',

    props: {
        synopsis: {
            type: Object,
            default: null,
        },
    },

    data() {
        return {
            title: null,
            pitch: null,
            categories: [],
            v$: useVuelidate(),
            loading: false,
        }
    },

    computed: {
        ...mapStores(useCategoryStore, useSynopsisStore),
    },

    validations () {
        return {
            title: { required, maxLength: maxLength(255), minLength: minLength(2) },
            pitch: { maxLength: maxLength(2500), minLength: minLength(3) },
            categories: { required }
        }
    },

    mounted () {
        if (this.synopsis !== null) {
            this.title = this.synopsis.title;
            this.pitch = this.synopsis.pitch;
            this.synopsis.categories.forEach(category => {
                this.categories.push(category.id);
            });
        }

        const choice = new Choices('#categories', {
            allowHTML: true,
            loadingText: 'Chargement...',
            removeItemButton: true,
            noResultsText: 'Aucun résultat',
            noChoicesText: 'Aucun élément',
            itemSelectText: 'Cliquer pour sélectionner',
        });

        const options = [];
        this.categoryStore.categories.forEach(element => {
            const isSelected = this.categories.includes(element.id) ? true : false;
            options.push({value: element.id, label: element.title, selected: isSelected});
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

            const data = {title: this.title, pitch: this.pitch, categories: this.categories};

            if (this.synopsis !== null) {
                await this.edit(data);
            } else {
                await this.create(data);
            }
        },

        async create(data) {
            const status = await this.synopsisStore.postSynopsis(data);
            if (!status) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
            } else {
                this.$router.push('/synopsis/' + this.synopsisStore.synopsis.id + '-' + this.synopsisStore.synopsis.slug);
            }
        },

        async edit(data) {
            data.description = this.synopsis.description;

            const status = await this.synopsisStore.putSynopsis(data, this.synopsis.id);
            if (!status) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
            } else {
                this.closeModal();
            }
        }
    },
}
</script>

<style>
#synopsisModal {
    display: block;
}

#synopsisModal .choices {
    margin-bottom: 0;
}
</style>