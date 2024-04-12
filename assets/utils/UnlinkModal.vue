<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="unlinkModal" tabindex="-1" aria-labelledby="unlinkModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5 text-danger" id="unlinkModalConfigLabel">Retirer un élément</h3>
                        <button type="button" class="btn-close" aria-label="fermeture" @click.prevent="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger fs-5 mb-0">
                            Attention, cette action est définitive ! Voulez-vous vraiment retirer 
                            l'élément <span class="fw-bold">{{ title }}</span> de ce synopsis ?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" @click.prevent="closeModal">
                            <i class="fa-solid fa-xmark fa-fw"></i> Annuler
                        </button>
                        <button type="button" class="btn btn-danger btn-sm" @click.prevent="accept">
                            <i class="fa-solid fa-spinner fa-spin fa-fw" v-if="loading"></i>
                            <i class="fa-solid fa-link-slash fa-fw" v-else></i>
                            Confirmer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'UnlinkModal',

        props: {
            title: String,
            loading: {
                type: Boolean,
                default: false
            }
        },

        methods: {
            closeModal() {
                this.$emit('on-close');
            },

            accept() {
                this.$emit('on-confirm');
            }
        },
    }
</script>

<style scoped>
#unlinkModal {
    display: block;
    z-index: 3000;
}

.modal-backdrop {
    z-index: 2080;
}
</style>