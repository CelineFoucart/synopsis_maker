<template>
    <div class="mb-2">
        <router-link :to="{ name: 'index' }" class="text-decoration-none">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </router-link>
    </div>

    <Error v-if="error"></Error>
    <article v-if="error === false && synopsisStore.synopsis !== null" >
        <SynopsisCard :synopsis="synopsisStore.synopsis" :showLink="false"></SynopsisCard>
        <!-- afficher les épisodes si l'auteur le veut -->
        <!-- afficher les personnages si l'auteur le veut -->
        <!-- afficher les lieux si l'auteur le veut -->
    </article>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&explore/stores/synopsis.js';
import SynopsisCard from '&explore/components/SynopsisCard.vue';
import { createToastify } from '&utils/toastify.js';
import Error from '&utils/Error.vue';
import dayjs from 'dayjs';
import 'dayjs/locale/fr';
dayjs.locale('fr');

export default {
    name: 'Synopsis',

    components: {
        Error,
        SynopsisCard
    },

    data() {
        return {
            error: false
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),
    },

    async mounted () {
        const status = await this.synopsisStore.getSynopsis(this.$route.params);

        if (!status) {
            createToastify("Ce synopsis n'existe pas.", 'error');
            this.error = true;
        }
    },

    methods: {
        formatDatetime(datetime) {
            return dayjs(datetime).format('DD MMMM YYYY');
        }
    },
}
</script>

<style scoped>
.pitch {
    white-space: pre-wrap;
}
</style>
