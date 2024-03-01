<template>
    <header>
        <div class="row">
            <div class="col-10">
                <h1 class="display-5">
                    <span class="me-2">{{ synopsis.title }}</span>
                    <span class="action-btn-edit text-success h5" v-tooltip="'Editer les informations'" @click="editInfoModal = true">
                        <i class="fa-solid fa-pen"></i>
                    </span>
                </h1>
                <span v-for="category in synopsis.categories" :key="category.id" class="badge me-1 bg-secondary">
                    {{ category.title }}
                </span>
            </div>
            <div class="col-2 text-end">
                <router-link :to="{ name: 'SynopsisIndex' }" class="btn btn-sm btn-dark" v-tooltip="'Liste'">
                    <i class="fa-solid fa-arrow-left"></i>
                </router-link>
                <div class="btn-group ms-1">
                    <button type="button" class="btn btn-sm btn-dark" v-tooltip="'Méta données'" @click="showModal = true">
                        <i class="fa-solid fa-circle-info"></i>
                    </button>
                </div>
            </div>
            <div class="col-12 mt-3">
                <p class="lead">{{ synopsis.pitch }}</p>
            </div>
        </div>
        <MetaDataModal :synopsis="synopsis" @on-close="showModal = false" v-if="showModal"></MetaDataModal>
        <AddSynopsisModal :synopsis="synopsis" v-if="editInfoModal" @on-close="editInfoModal = false"></AddSynopsisModal>
    </header>
</template>

<script>
import MetaDataModal from '&synopsis/components/synopsis_show/MetaDataModal.vue';
import AddSynopsisModal from '&synopsis/components/synopsis/AddSynopsisModal.vue';

export default {
    name: 'HeaderSynopsis',

    components: {
        MetaDataModal,
        AddSynopsisModal
    },

    props: {
        synopsis: Object,
    },

    data() {
        return {
            showModal: false,
            editInfoModal: false,
        }
    },
}
</script>

<style scoped>
p {
    white-space: pre-wrap
}

.action-btn-edit {
    cursor: pointer;
}
</style>
