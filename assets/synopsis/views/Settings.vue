<template>
    <article>
        <header class="mb-2">
            <h1 class="display-5 mb-4">Réglage et configuration</h1>
            <p class="lead">Gérer sur cette page vos réglages et les configurations de vos synopsis.</p>
            <hr>

            <nav class="settings-navbar">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link" :class="{active: showCategorySynopsis}" href="#" @click.prevent="navigateTo('synopsisCategory')">
                            <i class="fa-solid fa-tags fa-fw"></i>
                            <span class="d-none d-md-inline ps-1">Catégorie de synopsis</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" :class="{active: showArticleCategory}" @click.prevent="navigateTo('articleCategory')">
                            <i class="fa-solid fa-bookmark fa-fw"></i>
                            <span class="d-none d-md-inline ps-1">Catégorie d'article</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </header>

        <CategoryIndex v-if="showCategorySynopsis"></CategoryIndex>
    </article>
</template>

<script>
import CategoryIndex from '&synopsis/components/settings/CategoryIndex.vue';

export default {
    name: 'Settings',

    components: {
        CategoryIndex,
    },

    data() {
        return {
            showCategorySynopsis: true,
            showArticleCategory: false
        }
    },

    methods: {
        navigateTo(type) {
            this.showCategorySynopsis = false;
            this.showArticleCategory = false;

            switch (type) {
                case 'synopsisCategory':
                    this.showCategorySynopsis = true;
                    break;
                case 'articleCategory':
                    this.showArticleCategory = true;
                    break;
                default:
                    this.showCategorySynopsis = true;
                    break;
            }
        }
    },
}
</script>

<style scoped>
.settings-navbar {
    background-color: var(--bs-gray-100);
    color: var(--bs-gray-700);
}

.settings-navbar .nav-link {
    padding-top: 15px;
    padding-bottom: 15px;
}

.settings-navbar .nav-link {
    color: inherit;
    border-bottom: 2px solid var(--bs-gray-100);
}

.settings-navbar .nav-link.active {
    background-color: var(--bs-gray-100);
    border-color: var(--bs-gray-900);
    color: var(--bs-gray-900);
    border-radius: 0;
}
</style>