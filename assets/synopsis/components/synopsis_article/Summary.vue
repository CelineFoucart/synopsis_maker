<template>
    <aside>
        <h2 class="h5 pb-1 fw-bold border-bottom">Sommaire</h2>
        <div class="mb-2" v-for="category in articleCategoryStore.categories" :key="category.id">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="fw-bold"> <i :class="'fa-solid ' + category.icon + ' fa-fw'"></i> {{ category.title }}</span>
                    <div class="small text-muted" style="white-space: pre-wrap;">{{ category.description }}</div>
                </div>
                <div class="rounded p-1 bg-light" role="button"><i class="fa-solid fa-chevron-right fa-fw"></i> </div>
            </div>
        </div>
    </aside>
</template>

<script lang="js">
import { mapStores } from "pinia";
import { useArticleCategoryStore } from '&synopsis/stores/articleCategory.js';

export default {
    name: 'Summary',

    computed: {
        ...mapStores(useArticleCategoryStore),
    },
}
</script>