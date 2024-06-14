<template>
    <header class="py-4 text-center shadow-sm">
        <h1 class="display-5">Explorer</h1>
        <p class="fs-5 text-secondary">Explorer les synopsis partagés par les autres membres.</p>
    </header>
    <div class="container-fluid my-3">
        <router-view></router-view>
    </div>
    <Loading v-if="synopsisStore.loading || userStore.loading"></Loading>
</template>

<script>
import Loading from '&utils/Loading.vue';
import { mapStores } from "pinia";
import { useSynopsisStore } from '&explore/stores/synopsis.js';
import { useUserStore } from '&explore/stores/user.js';

export default {
    name: 'Explore',

    components: {
        Loading
    },

    computed: {
        ...mapStores(useSynopsisStore, useUserStore),
    },
}
</script>
