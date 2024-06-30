<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="episodeModal" tabindex="-1" aria-labelledby="episodeModalLabel">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5" id="episodeModalLabel">Episode</h3>
                        <button type="button" class="btn-close" aria-label="fermeture" @click.prevent="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <h4 class="h5 text-center mb-3" v-if="episode.chapter !== null">
                            Episode inclus dans <span class="fw-bold">{{ episode.chapter.title }}</span>
                        </h4>
                        <div class="d-flex gap-2">
                            <div class="mb-3 flex-fill">
                                <label for="title" class="form-label required">Titre</label>
                                <input type="text" class="form-control" id="title" v-model="title" :class="{ 'is-invalid': v$.title.$errors.length }">
                                <div class="invalid-feedback">
                                    Ce champ est obligatoire et doit faire entre 2 et 255 caractères.
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-label">Couleur</div>
                                <color-picker format="hex" v-model:pureColor="color" disable-alpha lang="En"/>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" rows="3" v-model="description" :class="{ 'is-invalid': v$.description.$errors.length }"></textarea>
                            <div class="form-text">Donnez une courte description de moins de 1500 caractères.</div>
                            <div class="invalid-feedback">
                                Ce champ doit faire entre 3 et 1500 caractères.
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="places" class="form-label">Lieux</label>
                                    <select id="places" v-model="places" multiple></select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="characters" class="form-label">Personnages</label>
                                    <select id="characters" v-model="characters" multiple></select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label mb-0">Contenu</label>
                            <Description v-model:data="content" :editMode="true"></Description>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" @click.prevent="closeModal">
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
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { createToastify } from '&utils/toastify.js';
import { required, maxLength, minLength  } from '@vuelidate/validators';
import { useVuelidate } from '@vuelidate/core';
import { ColorPicker } from "vue3-colorpicker";
import "vue3-colorpicker/style.css";
import Description from '&utils/Description.vue';
import 'choices.js/public/assets/styles/choices.css';
import Choices from 'choices.js';

export default {
    name: 'EpisodeModal',

    components: {
        ColorPicker,
        Description
    },

    props: {
        episode: Object,
    },

    data() {
        return {
            title: null,
            description: null,
            color: null,
            content: null,
            places: [],
            characters: [],
            v$: useVuelidate(),
            loading: false,
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),
    },

    validations () {
        return {
            title: { required, maxLength: maxLength(255), minLength: minLength(2) },
            description: { maxLength: maxLength(1500), minLength: minLength(3) },
        }
    },

    mounted () {
        this.title = this.episode.title;
        this.description = this.episode.description;
        this.color = this.episode.color;
        this.content = this.episode.content;
        this.hydratePlacesSelect();
        this.hydrateCharacterSelect();
    },

    methods: {
        closeModal() {
            this.$emit('on-close');
        },

        hydratePlacesSelect() {
            this.episode.places.forEach(place => {
                this.places.push(place.id);
            });
            const placeOptions = [];
            this.synopsisStore.synopsis.places.forEach(element => {
                const isSelected = this.places.includes(element.id) ? true : false;
                placeOptions.push({value: element.id, label: element.title, selected: isSelected});
            });
            this.setChoices('#places', placeOptions);
        },

        hydrateCharacterSelect() {
            this.episode.characters.forEach(character => {
                this.characters.push(character.id);
            });
            const characterOptions = [];
            this.synopsisStore.synopsis.characters.forEach(element => {
                const isSelected = this.characters.includes(element.id) ? true : false;
                characterOptions.push({value: element.id, label: element.name, selected: isSelected});
            });
            this.setChoices('#characters', characterOptions);
        },

        setChoices(id, options) {
            const choice = new Choices(id, {
                allowHTML: true,
                loadingText: 'Chargement...',
                removeItemButton: true,
                noResultsText: 'Aucun résultat',
                noChoicesText: 'Aucun élément',
                itemSelectText: 'Cliquer pour sélectionner',
            });

            choice.clearChoices();
            choice.setChoices(options);
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
                description: this.description, 
                color: this.color, 
                content: this.content,
                places: this.places,
                characters: this.characters
            };

            let status;
            if (this.episode.id === null) {
                const chapterId = this.episode.chapter ? this.episode.chapter.id : 0;
                status = await this.synopsisStore.postEpisode(data, chapterId);
            } else {
                status = await this.synopsisStore.putEpisode(data, this.episode.id);
            }

            if (!status) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
            } else {
                this.closeModal();
            }
        }
    },
}
</script>

<style scoped>
#episodeModal {
    display: block;
    z-index: 1080;
}

.modal::-webkit-scrollbar {
    display: none;
}

.modal {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
</style>