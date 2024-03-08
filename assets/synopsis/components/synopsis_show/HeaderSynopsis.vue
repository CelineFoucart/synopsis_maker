<template>
    <header>
        <div class="row">
            <div class="col-7">
                <h1 class="display-5">
                    <span class="me-2">{{ synopsis.title }}</span>
                    <span class="action-btn-header text-success h5 me-2" role="button" v-tooltip="'Editer les informations'" @click="editInfoModal = true">
                        <i class="fa-solid fa-pen"></i>
                    </span>
                </h1>
            </div>
            <div class="col-5 text-end">
                <router-link :to="{ name: 'SynopsisIndex' }" class="btn btn-sm btn-dark" v-tooltip="'Liste'">
                    <i class="fa-solid fa-arrow-left fa-fw"></i>
                </router-link>
                <div class="btn-group ms-1">
                    <router-link :to="{ name: 'SynopsisShow', params:{slug: synopsis.slug, id: synopsis.id} }" class="btn btn-sm btn-dark" v-tooltip="'Gérer le synopsis'">
                        <i class="fa-solid fa-pen-to-square fa-fw"></i>
                    </router-link>
                    <router-link :to="{ name: 'SynopsisEpisodes', params:{slug: synopsis.slug, id: synopsis.id} }" class="btn btn-sm btn-dark" v-tooltip="'Episodes'">
                        <i class="fa-solid fa-copy fa-fw"></i>
                    </router-link>
                    <button type="button" class="btn btn-danger btn-sm" v-tooltip="'Supprimer'" @click="deleteModal = true">
                        <i class="fa-solid fa-trash fa-fw"></i>
                    </button>
                </div>
            </div>
            <div class="col-12">
                <span v-for="category in synopsis.categories" :key="category.id" class="badge me-1 bg-secondary">
                    {{ category.title }}
                </span>
            </div>
        </div>
        <AddSynopsisModal :synopsis="synopsis" v-if="editInfoModal" @on-close="editInfoModal = false"></AddSynopsisModal>
        <Delete :title="synopsis.title" v-if="deleteModal" @on-confirm="deleteSynopsis" @on-close="deleteModal = false"></Delete>
    </header>
</template>

<script>
import AddSynopsisModal from '&synopsis/components/synopsis/AddSynopsisModal.vue';
import Delete from '&utils/Delete.vue';

export default {
    name: 'HeaderSynopsis',

    components: {
        AddSynopsisModal,
        Delete
    },

    props: {
        synopsis: Object,
    },

    data() {
        return {
            editInfoModal: false,
            deleteModal: false
        }
    },

    methods: {
        deleteSynopsis() {
            this.$emit('on-delete');
            this.deleteModal = false;
        }
    },
}
</script>

<style scoped>
.action-btn-header {
    cursor: pointer;
}
</style>
