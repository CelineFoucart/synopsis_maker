<template>
    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Liste des membres</h1>
                    <button type="button" class="btn-close" @click="onClose" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center text-muted my-3" v-if="loading">
                        <i class="fas fa-spinner fa-spin fa-6x"></i>
                    </div>
                    <div v-if="loading === false">
                        <div class="input-group mb-2">
                            <span class="input-group-text">
                                <label for="searchValue">Recherche</label>
                            </span>
                            <input type="text" class="form-control w-auto input-sm" id="searchValue" v-model="searchValue" />
                        </div>
                        <DataTable :items="userStore.users" 
                            :headers="headers" 
                            :rows-items="[10, 25, 50, 100]"
                            empty-message="Aucun résultat"
                            rows-per-page-message="Elément par page"
                            rows-of-page-separator-message="sur"
                            :search-field="searchField"
                            :search-value="searchValue"
                            :buttons-pagination="true"
                            show-index
                            theme-color="#0d6efd"
                            sort-by="username"
                            alternating>
                            <template #item-createdAt="{ createdAt }">
                                {{formatDatetime(createdAt)}}
                            </template>

                            <template #item-operation="item">
                                <router-link :to="{ name: 'user_show', params:{id: item.id} }" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye fa-fw"></i> Voir
                                </router-link>
                            </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="js">
import { Modal } from 'bootstrap';
import { useUserStore } from '&explore/stores/user.js';
import { mapStores } from "pinia";
import { createToastify } from '&utils/toastify.js';
import Vue3EasyDataTable from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';
import dayjs from 'dayjs';
import 'dayjs/locale/fr';
dayjs.locale('fr');

export default {
    name: 'UserListModal',

    components: {
        DataTable: Vue3EasyDataTable,
    },

    data() {
        return {
            loading: true,
            modal: null,
            headers: [ 
                {text: 'Pseudo', value: 'username', sortable: true},
                {text: 'Inscription', value: 'createdAt', sortable: true},
                {text: "Actions", value: "operation"},
            ],
            searchField: 'username',
            searchValue: ''
        }
    },

    computed: {
        ...mapStores(useUserStore),
    },

    async mounted () {
        this.modal = new Modal('#userModal', { keyboard: false });
        this.modal.show();

        const status = await this.userStore.getUsers();
        if (!status) {
            createToastify("Le chargement des auteurs a échoué.", 'error');
        }

        this.loading = false;
    },

    beforeUnmount() {
        this.modal.hide();
    },

    methods: {
        onClose() {
            this.$emit('on-close');
        },

        formatDatetime(datetime) {
            return dayjs(datetime).format('DD MMMM YYYY HH:mm');
        }
    },
}
</script>
