<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5" id="categoryModalConfigLabel">Catégorie</h3>
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
                            <div class="invalid-feedback">
                                Ce champ doit faire entre 3 et 2500 caractères.
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
import { useVuelidate } from '@vuelidate/core'
import { required, maxLength, minLength  } from '@vuelidate/validators'
import { createToastify } from '&utils/toastify.js';
import { mapStores } from "pinia";
import { useCategoryStore } from '&synopsis/stores/category.js';

export default {
    name: 'CategoryModal',

    props: {
        category: Object,
    },

    data() {
        return {
            title: null,
            description: null,
            v$: useVuelidate(),
            loading: false,
        }
    },

    computed: {
        ...mapStores(useCategoryStore),
    },

    validations () {
        return {
            title: { required, maxLength: maxLength(255), minLength: minLength(2) },
            description: { maxLength: maxLength(2500), minLength: minLength(3) },
        }
    },

    mounted () {
        this.title = this.category.title;
        this.description = this.category.description;
    },

    methods: {
        closeModal() {
            this.$emit('on-close');
        },

        async save() {
            this.loading = true;
            const result = await this.v$.$validate()
            if (!result) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
                return;
            }

            let status;
            const data = {title: this.title, description: this.description};
            if (this.category.id !== null) {
                status = await this.categoryStore.putCategory(this.category.id, data);
            } else {
                status = await this.categoryStore.postCategory(data);
            }

            if (status) {
                createToastify('La catégorie a bien été créée.', 'success');
                this.closeModal();
            } else {
                createToastify('La sauvegarde a échoué. Vérifiez que le formulaire est valide.', 'error');
            }

            this.loading = false;
            return;
        }
    },
}
</script>

<style scoped>
#categoryModal {
    display: block;
}
</style>