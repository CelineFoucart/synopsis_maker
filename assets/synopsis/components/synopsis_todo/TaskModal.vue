<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="chapterModal" tabindex="-1" aria-labelledby="chapterModalLabel">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5" id="chapterModalConfigLabel">Tâche dans la liste <strong>{{ category }}</strong></h3>
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
                            <label for="category" class="form-label required">Statut</label>
                            <select class="form-select" id="category" v-model="status">
                                <option value="0">A faire</option>
                                <option value="1">En cours</option>
                                <option value="2">Terminé</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Description</label>
                            <textarea class="form-control" id="content" v-model="content" :class="{ 'is-invalid': v$.content.$errors.length }"></textarea>
                            <div class="form-text">Donnez une courte description de moins de 1500 caractères.</div>
                            <div class="invalid-feedback">
                                Ce champ doit faire entre 3 et 1500 caractères.
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
import { required, maxLength, minLength, minValue, maxValue  } from '@vuelidate/validators';
import { useVuelidate } from '@vuelidate/core';
import "vue3-colorpicker/style.css";
export default {
    name: 'TaskModal',

    props: {
        task: Object,
        category: String,
    },

    data() {
        return {
            title: null,
            content: null,
            status: null,
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
            content: { maxLength: maxLength(1500), minLength: minLength(3) },
            status: { required, minValue: minValue(0), maxValue: maxValue(2) }
        }
    },

    mounted () {
        this.title = this.task.title;
        this.content = this.task.content;
        this.status = this.task.category;
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
                id: this.task.id, 
                title: this.title, 
                content: this.content, 
                category: this.status,
                position: this.task.position
            };

            const status = await this.synopsisStore.addTask(data);
            if (!status) {
                createToastify("L'enregistrement a échoué.", 'error');
            }

            this.loading = false;
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