<template>
    <div>
        <Error v-if="error"></Error>
        <article v-if="!loading && synopsisStore.synopsis !== null">
            <HeaderSynopsis :synopsis="synopsisStore.synopsis"></HeaderSynopsis>
            <div class="row align-items-center mt-4">
                <div class="col-md-8">
                    <h2 class="h5 mb-0 text-secondary">Episodes et chapitres archivés</h2>
                </div>
                <div class="col-md-4 text-end">
                    <button class="btn btn-sm btn-dark me-1" @click.prevent="openAll = !openAll" v-tooltip="openAll ? 'Tout ouvrir' : 'Tout fermer'">
                        <i class="fa-solid fa-folder-open fa-fw" v-if="!openAll"></i>
                        <i class="fa-solid fa-folder-closed fa-fw" v-if="openAll"></i>
                    </button>
                </div>
            </div>
            <SynopsisElementList :openAll="openAll" :archived="true"></SynopsisElementList>
        </article>
        <Loading v-if="loading"></Loading>
    </div>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { useCategoryStore } from '&synopsis/stores/category.js';
import { createToastify } from '&utils/toastify.js';
import Loading from '&utils/Loading.vue';
import Error from '&utils/Error.vue';
import HeaderSynopsis from '&synopsis/components/synopsis_show/HeaderSynopsis.vue';
import SynopsisElementList from '&synopsis/components/synopsis_show/SynopsisElementList.vue';

export default {
    name: 'SynopsisArchive',

    components: {
        Loading,
        HeaderSynopsis,
        Error,
        SynopsisElementList
    },

    data() {
        return {
            error: false,
            loading: false,
            openAll: true
        }
    },

    computed: {
        ...mapStores(useSynopsisStore, useCategoryStore),
    },

    async mounted () {
        if (this.synopsisStore.synopsis !== null) {
            return;
        }

        this.loading = true;
        const status = await this.synopsisStore.getSynopsis(this.$route.params);
        if (!status) {
            createToastify("Ce synopsis n'existe pas.", 'error');
            this.error = true;
        } else {
            this.legend = this.synopsisStore.synopsis.legend;
        }

        const statusCategory = await this.categoryStore.getCategories();
        if (!statusCategory) {
            createToastify("Le chargement des catégories a échoué.", 'error');
        }
        
        this.loading = false;
    },
}
</script>
