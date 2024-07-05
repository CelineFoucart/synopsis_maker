<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="homepageModal" tabindex="-1" aria-labelledby="homepageModalLabel">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5" id="homepageModalLabel">Page d'accueil de l'encyclopédie</h3>
                        <button type="button" class="btn-close" aria-label="fermeture" @click.prevent="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <label for="description" class="form-label mb-0">Contenu de la page d'accueil</label>
                        <Description v-model:data="homepage" :editMode="true"></Description>
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
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { createToastify } from '&utils/toastify.js';
import Description from '&utils/Description.vue';

export default {
    name: 'HomePageModal',

    components: {
        Description,
    },

    props: {
        synopsis: Object
    },

    data() {
        return {
            homepage: null,
            loading: false,
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),
    },

    mounted () {
        this.homepage = this.synopsis.worldbuildingHome;
    },

    methods: {
        closeModal() {
            this.$emit('on-close');
        },

        async save() {
            this.loading = true;

            const status = await this.synopsisStore.editHomePage(this.synopsis.id, this.homepage);
            if (!status) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
            }

            this.loading = false;
        }
    },
}
</script>

<style scoped>
#homepageModal {
    display: block;
}
</style>