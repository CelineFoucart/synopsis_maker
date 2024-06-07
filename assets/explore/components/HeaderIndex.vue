<template>
    <header class="pb-3">
        <div class="row align-items-end g-3">
            <div class="col-md-8">
                <h2 class="h4 mb-3">Synopsis publiés par les membres</h2>
                <span class="sort-title small rounded me-2">Trier par :</span>
                <a href="#" class="filter btn btn-sm" :class="{'active': field === 's.title'}" @click="field = 's.title'">
                    <i class="fas fa-file-alt fa-fw"></i> titre
                </a>
                <a href="#" class="filter btn btn-sm" :class="{'active': field === 's.createdAt'}" @click="field = 's.createdAt'">
                    <i class="fas fa-calendar-alt fa-fw"></i>date
                </a>
                <span class="border-start ps-1 ms-1">
                    <a href="#" class="filter btn btn-sm" v-tooltip="'Ascendant'" :class="{'active': sort === 'asc'}" @click="sort = 'asc'">
                        <i class="fas fa-sort-amount-up-alt fa-fw"></i>
                    </a>
                    <a href="#" class="filter btn btn-sm" v-tooltip="'Descendant'" :class="{'active': sort === 'desc'}"  @click="sort = 'desc'">
                        <i class="fas fa-sort-amount-down-alt fa-fw"></i>
                    </a>
                </span>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" id="search-input" class="form-control input-sm" v-model="query" placeholder="Rechercher...">
                    <button class="btn btn-primary" v-tooltip="'Rechercher'" @click="getSynopses()">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-12">
                <Pagination :pagination="synopsisStore.pagination" @on-change="onPaginationChange"></Pagination>
            </div>
        </div>
    </header>
</template>

<script>
import Pagination from '&utils/Pagination.vue';
import { useSynopsisStore } from '&explore/stores/synopsis.js';
import { mapStores } from "pinia";
import { createToastify } from '&utils/toastify.js';

export default {
    name: 'HeaderIndex',

    components: {
        Pagination,
    },

    data() {
        return {
            query: null,
            field: 's.title',
            sort: 'asc',
            page: 1,
            limit: 10
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),
    },

    watch: {
        async field() {
            await this.getSynopses();
        },

        async sort() {
            await this.getSynopses();
        }
    },

    async mounted () {
        await this.getSynopses();
    },

    methods: {
        async onPaginationChange(data) {
            this.page = data.page;
            this.limit = data.limit;
            await this.getSynopses();
        },

        async getSynopses() {
            const status = await this.synopsisStore.getSynopses(this.page, this.limit, this.field, this.sort, this.query);
            if (!status) {
                createToastify("Le chargement des synopsis a échoué.", 'error');
            }
        },
    },
}
</script>

<style scoped>
.sort-title {
    padding: 0;
}
.filter {
    background-color: transparent;
    border: 1px solid transparent;
    font-weight: bold;
    color: inherit;
    text-decoration: none;
}

.filter:hover {
    background-color: rgb(248, 249, 250);
}

.filter.active {
    border: 1px solid rgb(33, 37, 41);
}

</style>
