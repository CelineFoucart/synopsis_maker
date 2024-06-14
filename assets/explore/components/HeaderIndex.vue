<template>
    <header class="pb-3">
        <div class="row align-items-end g-3">
            <div class="col-md-6">
                <h2 class="h4 mb-3">Synopsis publiés par les membres</h2>
                <span class="sort-title small rounded me-2">Trier par :</span>
                <a href="#" class="filter btn btn-sm" :class="{'active': field === 's.title'}" @click="field = 's.title'">
                    <i class="fas fa-file-alt fa-fw"></i> titre
                </a>
                <a href="#" class="filter btn btn-sm" :class="{'active': field === 's.createdAt'}" @click="field = 's.createdAt'">
                    <i class="fas fa-calendar-alt fa-fw"></i>date
                </a>
                <span class="border-start ps-1 ms-1">
                    <a href="#" class="filter btn btn-sm" v-tooltip="tooltipAsc" :class="{'active': sort === 'asc'}" @click="sort = 'asc'">
                        <i class="fas fa-sort-alpha-up" v-if="field === 's.title'"></i>
                        <i class="fas fa-sort-amount-up-alt fa-fw" v-else></i>
                    </a>
                    <a href="#" class="filter btn btn-sm" v-tooltip="tooltipDesc" :class="{'active': sort === 'desc'}"  @click="sort = 'desc'">
                        <i class="fas fa-sort-alpha-down-alt" v-if="field === 's.title'"></i>
                        <i class="fas fa-sort-amount-down-alt fa-fw" v-else></i>
                    </a>
                </span>
            </div>
            <div class="col-md-6 d-flex gap-2">
                <button class="btn btn-outline-secondary btn-sm" v-tooltip="'Membres'" @click="showUserList = true">
                    <i class="fas fa-users fa-fw"></i>
                    <div class="visually-hidden">Membres</div>
                </button>
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

        <UserListModal @on-close="showUserList = false" v-if="showUserList === true"></UserListModal>
    </header>
</template>

<script>
import Pagination from '&utils/Pagination.vue';
import { useSynopsisStore } from '&explore/stores/synopsis.js';
import { mapStores } from "pinia";
import { createToastify } from '&utils/toastify.js';
import UserListModal from '&explore/components/UserListModal.vue';

export default {
    name: 'HeaderIndex',

    components: {
        Pagination,
        UserListModal
    },

    data() {
        return {
            query: null,
            field: 's.title',
            sort: 'asc',
            page: 1,
            limit: 10,
            showUserList: false
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),

        tooltipAsc() {
            if (this.field === 's.title') {
                return "Trier de A à Z";
            }

            return "Trier du plus ancien au plus récent";
        },

        tooltipDesc() {
            if (this.field === 's.title') {
                return "Trier de Z à A";
            }

            return "Trier du plus récent au plus ancien";
        }
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
