<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="chapterModal" tabindex="-1" aria-labelledby="chapterModalLabel">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5" id="chapterModalConfigLabel">Synopsis</h3>
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
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" rows="3" v-model="description" :class="{ 'is-invalid': v$.description.$errors.length }"></textarea>
                            <div class="form-text">Donnez une courte description de moins de 1500 caractères.</div>
                            <div class="invalid-feedback">
                                Ce champ doit faire entre 3 et 1500 caractères.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="color" class="form-label required">Couleur</label>
                            <input type="color" class="form-control" id="color" v-model="color" :class="{ 'is-invalid': v$.color.$errors.length }">
                            <div class="invalid-feedback">
                                Ce champ est obligatoire et doit faire entre 2 et 30 caractères.
                            </div>
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
import { useVuelidate } from '@vuelidate/core'

export default {
    name: 'ChapterModal',

    props: {
        chapter: Object,
    },

    data() {
        return {
            title: null,
            description: null,
            color: null,
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
            color: { maxLength: maxLength(30), minLength: minLength(3) },
        }
    },

    mounted () {
        this.title = this.chapter.title;
        this.description = this.chapter.description;
        this.color = this.chapter.color;
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
                title: this.title, description: this.description, color: this.color
            };

            let status;
            if (this.chapter.id === null) {
                status = await this.synopsisStore.postChapter(data);
            } else {
                status = await this.synopsisStore.putChapter(data, this.chapter.id);
            }

            if (!status) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
            }

            this.closeModal();
        }
    },
}
</script>

<style scoped>
#chapterModal {
    display: block;
    z-index: 3000;
}
</style>