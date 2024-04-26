<template>
    <Error v-if="error"></Error>
    <article v-if="synopsisStore.synopsis !== null">
        <HeaderSynopsis :synopsis="synopsisStore.synopsis"></HeaderSynopsis>

    </article>
</template>

<script lang="js">
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { createToastify } from '&utils/toastify.js';
import Error from '&utils/Error.vue';
import HeaderSynopsis from '&synopsis/components/synopsis_show/HeaderSynopsis.vue';

export default {
    name: 'WorldBuilding',

    components: {
        HeaderSynopsis,
        Error
    },

    data() {
        return {
            error: false,
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),
    },

    async mounted () {
        if (this.synopsisStore.synopsis !== null) {
            return;
        }
        
        const status = await this.synopsisStore.getSynopsis(this.$route.params);
        if (!status) {
            createToastify("Ce synopsis n'existe pas.", 'error');
            this.error = true;
        }
    },
}
</script>
