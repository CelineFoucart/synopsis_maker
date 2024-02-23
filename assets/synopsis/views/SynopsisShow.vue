<template>
    <div>
        <article class="alert alert-danger" v-if="error">
            <h2 class="h5">Erreur 404</h2>
            Ce synopsis n'existe pas.
        </article>
        <article v-else>
            <header>
                <div class="row">
                    <div class="col-10">
                        <h2>Titre</h2>
                    </div>
                    <div class="col-2 text-end">
                        <div class="btn-group">
                            <router-link :to="{ name: 'SynopsisIndex' }" class="btn btn-sm btn-secondary" v-tooltip="'Liste'">
                                <i class="fa-solid fa-arrow-left"></i>
                            </router-link>
                        </div>
                    </div>
                </div>
            </header>
        </article>
    </div>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { createToastify } from '&utils/toastify.js';

export default {
    name: 'SynopsisShow',

    data() {
        return {
            error: false
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),
    },
    
    async mounted () {
        if (this.synopsisStore.synopsis !== null) {
            return;
        }

        this.error = await this.synopsisStore.getSynopsis(this.$route.params);
        if (this.error) {
            createToastify("Ce synopsis n'existe pas.", 'error');
        }
    },
}
</script>