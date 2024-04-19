<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="placeModal" tabindex="-1" aria-labelledby="placeModalLabel">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5" id="placeModalLabel">Lieu</h3>
                        <button type="button" class="btn-close" aria-label="fermeture" @click.prevent="closeModal"></button>
                    </div>
                    <div class="modal-body bg-light">
                        <div class="mb-3">
                            <label for="title" class="form-label required">Nom</label>
                            <input type="text" class="form-control" id="title" v-model="title" :class="{ 'is-invalid': v$.title.$errors.length }">
                            <div class="invalid-feedback">
                                Ce champ est obligatoire et doit faire entre 2 et 255 caractères.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Rôle dans l'histoire</label>
                            <textarea class="form-control" id="role" v-model="role" :class="{ 'is-invalid': v$.role.$errors.length }"></textarea>
                            <div class="invalid-feedback">
                                Ce champ doit faire entre 5 et 15000 caractères.
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label mb-0">Description (géographie, histoire, caractéristiques...)</label>
                            <Description v-model:data="description" :saveButton="false"></Description>
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">Lien</label>
                            <input type="url" class="form-control" id="link" v-model="link" :class="{ 'is-invalid': v$.link.$errors.length }">
                            <div class="form-text">Ajoutez un lien vers un article ou une page de blog sur ce lieu.</div>
                            <div class="invalid-feedback">
                                Ce champ doit faire entre 10 et 255 caractères et est être une URL valide.
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

<script lang="js">
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { usePlaceStore } from '&synopsis/stores/place.js';
import { createToastify } from '&utils/toastify.js';
import { required, maxLength, minLength, url  } from '@vuelidate/validators';
import { useVuelidate } from '@vuelidate/core';
import Description from '&utils/Description.vue';

export default {
    name: 'PlaceModal',

    components: {
        Description,
    },

    props: {
        data: Object
    },

    data() {
        return {
            title: null,
            role: null,
            link: null,
            description: '',
            v$: useVuelidate(),
            loading: false,
        }
    },

    computed: {
        ...mapStores(useSynopsisStore, usePlaceStore),
    },

    validations () {
        return {
            title: { required, maxLength: maxLength(255), minLength: minLength(2) },
            link: { maxLength: maxLength(255), minLength: minLength(10), url },
            role: { maxLength: maxLength(15000), minLength: minLength(5) },
        }
    },

    mounted () {
        this.title = this.data.title;
        this.link = this.data.link;
        this.role = this.data.role;
        this.description = this.data.description;
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
                role: this.role, 
                description: this.description,
            };

            let success = false;
            if (this.data.id) {
                success = await this.placeStore.edit(this.data.id, data);
            } else {
                success = await this.synopsisStore.addPlace(data);
            }

            if (!success) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
                this.loading = false;
            } else {
                this.closeModal();
            }
        },
    },
}
</script>

<style scoped>
#placeModal {
    display: block;
    z-index: 3000;
}
</style>