<template>
    <div class="row">
        <div class="col-12">
            <h1 class="display-5 mb-3">Mes synopsis</h1>
        </div>
        <div class="col-md-8">
            <div class="d-flex justify-content between flex-column h-100">
                <div class="text-end">
                    <button type="button" class="btn btn-sm btn-success" v-tooltip="'Ajouter'" @click="showAddModal = true">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
                <div class="flex-fill">
                    <SynopsisList v-if="!loading"></SynopsisList>
                </div>
                <Pagination :pagination="synopsisStore.pagination" @on-change="onPaginationChange"></Pagination>
            </div>
        </div>
        <div class="col-md-4 border-start p-3">
            <AsideSynopsis @on-loading="onLoading" v-model:filters="filters" @on-change="onChangeFilters"></AsideSynopsis>
        </div>
    </div>
    <AddSynopsisModal v-if="showAddModal" @on-close="showAddModal = false"></AddSynopsisModal>
    <Loading v-if="loading"></Loading>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import Loading from '&utils/Loading.vue';
import { createToastify } from '&utils/toastify.js';
import Pagination from '&utils/Pagination.vue';
import AsideSynopsis from '&synopsis/components/synopsis/AsideSynopsis.vue';
import SynopsisList from '&synopsis/components/synopsis/SynopsisList.vue';
import AddSynopsisModal from '&synopsis/components/synopsis/AddSynopsisModal.vue';

export default {
    name: 'SynopsisIndex',

    components: {
        Pagination,
        Loading,
        AsideSynopsis,
        SynopsisList,
        AddSynopsisModal
    },

    data() {
        return {
            loading: true,
            filters: {
                query: null,
                selectedCategories: []
            },
            page: 1,
            limit: 10,
            showAddModal: false,
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),
    },

    methods: {
        onLoading(loading) {
            this.loading = loading;
        },

        async getSynopses() {
            this.loading = true;
            const status = await this.synopsisStore.getSynopses(this.page, this.limit, this.filters);
            if (!status) {
                createToastify("Le chargement des synopsis a échoué", 'error');
            }
            this.loading = false;
        },

        onChangeFilters(payload) {
            this.filters.query = payload.query;
            this.filters.selectedCategories = payload.selectedCategories;
            this.getSynopses();
        },

        onPaginationChange(data) {
            this.page = data.page;
            this.limit = data.limit;
            this.getSynopses();
        },
    },
}
</script>
