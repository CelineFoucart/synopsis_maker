<template>
    <div>
        <div class="text-center fs-5" v-if="isEmpty">
            Aucun synopsis n'a été trouvé. Cliquez sur le bouton <strong><i class="fa-solid fa-plus"></i></strong> pour en ajouter un.
        </div>
        <div class="row" v-else>
            <article class="col-sm-6 col-md-4 col-lg-3" v-for="synopsis in synopsisStore.synopses">
                {{ synopsis.title }}
            </article>
        </div>
    </div>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';

export default {
    name: 'SynopsisList',

    computed: {
        ...mapStores(useSynopsisStore),

        isEmpty() {
            return this.synopsisStore.synopses.length === 0;
        },
    },
}
</script>