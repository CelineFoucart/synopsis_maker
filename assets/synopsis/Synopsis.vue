<template>
    <div>
        <Navigation></Navigation>
        <div class="container-fluid my-4">
            <router-view></router-view>
        </div>
        
        <Loading v-if="synopsisStore.loading || categoryStore.loading || articleCategoryStore.loading || articleStore.loading"></Loading>
    </div>
</template>

<script>
import Navigation from '&synopsis/components/Navigation.vue'
import Loading from '&utils/Loading.vue';
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { useCategoryStore } from '&synopsis/stores/category.js';
import { useArticleCategoryStore } from '&synopsis/stores/articleCategory.js';
import { useArticleStore } from '&synopsis/stores/article.js';

export default {
    name: 'Synopsis',
    
    components: {
        Navigation,
        Loading,
    },

    computed: {
        ...mapStores(useSynopsisStore, useCategoryStore, useArticleCategoryStore, useArticleStore),
    },
}
</script>
