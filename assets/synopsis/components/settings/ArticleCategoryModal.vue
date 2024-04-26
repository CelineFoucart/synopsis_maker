<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5" id="categoryModalConfigLabel">Catégorie d'article</h3>
                        <button type="button" class="btn-close" aria-label="fermeture" @click.prevent="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label required">Titre</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="title" v-model="title" :class="{ 'is-invalid': v$.title.$errors.length }">
                                <span class="input-group-text">
                                    <i :class="icon + ' fa-fw'"></i>
                                </span>
                            </div>
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
                        <IconSelect :selected="icon" @on-update="onUpdateIcon"></IconSelect>
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
import { useArticleCategoryStore } from '&synopsis/stores/articleCategory.js';
import IconSelect from '&synopsis/components/settings/IconSelect.vue';

export default {
    name: 'ArticleCategoryModal',

    components: {
        IconSelect,
    },

    props: {
        category: Object,
    },

    data() {
        return {
            title: null,
            icon: null,
            description: null,
            v$: useVuelidate(),
            loading: false
        }
    },

    computed: {
        ...mapStores(useArticleCategoryStore),
    },

    validations () {
        return {
            title: { required, maxLength: maxLength(255), minLength: minLength(2) },
            description: { maxLength: maxLength(2500), minLength: minLength(3) },
        }
    },

    mounted () {
        this.title = this.category.title;
        this.icon = this.category.icon;
        this.description = this.category.description;
    },

    methods: {
        closeModal() {
            this.$emit('on-close');
        },

        onUpdateIcon(icon) {
            this.icon = icon;
        },

        async save() {
            this.loading = true;
            const result = await this.v$.$validate()
            if (!result) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
                return;
            }

            let status;
            const data = {icon: this.icon, title: this.title, description: this.description};
            if (this.category.id !== null) {
                status = await this.articleCategoryStore.putCategory(this.category.id, data);
            } else {
                status = await this.articleCategoryStore.postCategory(data);
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
    z-index: 3000;
}

.modal-backdrop {
    z-index: 2080;
}
</style>