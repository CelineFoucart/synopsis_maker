<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5 text-primary" id="archiveModalConfigLabel">Archivage</h3>
                        <button type="button" class="btn-close" aria-label="fermeture" @click.prevent="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="fs-5 mb-0">
                            Voulez-vous vraiment {{elementToArchive.archived ?  'désarchiver' : 'archiver' }} 
                            {{ elementToArchive.type === 'episode' ? "l'épisode" : 'le chapitre' }} 
                            <span class="fw-bold">{{ elementToArchive.title }}</span> ?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" @click.prevent="closeModal">
                            <i class="fa-solid fa-xmark fa-fw"></i> Annuler
                        </button>
                        <button type="button" class="btn btn-primary btn-sm" @click.prevent="accept">
                            <i class="fa-solid fa-spinner fa-spin fa-fw" v-if="loading"></i>
                            <i class="fa-solid fa-box-archive fa-fw" v-else></i>
                            Confirmer
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

export default {
    name: "ArchiveModal",

    props: {
        elementToArchive: Object
    },

    data() {
        return {
            loading: false
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),
    },

    methods: {
        closeModal() {
            this.$emit('on-close');
        },

        async accept() {
            this.loading = true;

            const status = await this.synopsisStore.archiveAction(this.elementToArchive.id, this.elementToArchive.type);
            if (!status) {
                createToastify("L'opération a échoué.", 'error');
            } else {
                createToastify("L'élement a été archivé.", 'success');
            }
            this.closeModal();

            this.loading = false;
        }
    },
}
</script>

<style scoped>
#archiveModal {
    display: block;
}
</style>