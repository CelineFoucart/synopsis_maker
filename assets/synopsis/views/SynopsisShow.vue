<template>
    <div>
        <div class="alert alert-danger" v-if="error">
            <h2 class="h5">Erreur 404</h2>
            Ce synopsis n'existe pas.
        </div>
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