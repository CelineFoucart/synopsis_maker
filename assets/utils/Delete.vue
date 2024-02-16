<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5 text-danger" id="modalDeleteConfigLabel">Suppression</h3>
                        <button type="button" class="btn-close" aria-label="fermeture" @click.prevent="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger fw-bold fs-5 mb-0">
                            Attention, toute suppression est définitive ! Voulez-vous vraiment effectuer cette opération ?
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" @click.prevent="closeModal">Annuler</button>
                        <button type="button" class="btn btn-danger btn-sm" @click.prevent="accept">Confirmer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "DeleteModal",

        methods: {
            closeModal() {
                this.$emit('on-cancel');
            },

            accept() {
                this.$emit('on-confirm');
            }
        },
    }
</script>

<style scoped>
#modalDelete {
    display: block;
    z-index: 3000;
}

.modal-backdrop {
    z-index: 2080;
}
</style>