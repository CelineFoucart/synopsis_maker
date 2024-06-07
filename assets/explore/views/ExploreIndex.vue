<template>
    <article>
        <HeaderIndex></HeaderIndex>
        <div class="row g-3">
            <div class="col-md-6" v-for="synopsis in synopsisStore.synopses" :key="synopsis.id">
                <SynopsisCard :synopsis="synopsis" />
            </div>
        </div>
    </article>
</template>

<script>
import HeaderIndex from '&explore/components/HeaderIndex.vue';
import SynopsisCard from '&explore/components/SynopsisCard.vue';
import { mapStores } from "pinia";
import { useSynopsisStore } from '&explore/stores/synopsis.js';

export default {
    name: 'ExploreIndex',

    components: {
        HeaderIndex,
        SynopsisCard
    },

    computed: {
        ...mapStores(useSynopsisStore),
    },
}
</script>
