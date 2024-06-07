<template>
    <article>
        <HeaderIndex></HeaderIndex>
        <div class="row g-3">
            <div class="col-md-6" v-for="synopsis in synopsisStore.synopses" :key="synopsis.id">
                <SynopsisCard :synopsis="synopsis" />
            </div>
        </div>
    </article>
    <Loading v-if="synopsisStore.loading"></Loading>
</template>

<script>
import HeaderIndex from '&explore/components/HeaderIndex.vue';
import SynopsisCard from '&explore/components/SynopsisCard.vue';
import Loading from '&utils/Loading.vue';
import { mapStores } from "pinia";
import { useSynopsisStore } from '&explore/stores/synopsis.js';

export default {
    name: 'ExploreIndex',

    components: {
        HeaderIndex,
        Loading,
        SynopsisCard
    },

    computed: {
        ...mapStores(useSynopsisStore),
    },
}
</script>
