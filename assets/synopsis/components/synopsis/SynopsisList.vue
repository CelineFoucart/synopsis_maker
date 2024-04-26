<template>
    <div>
        <div class="text-center fs-5" v-if="isEmpty">
            Aucun synopsis n'a été trouvé. Cliquez sur le bouton <strong><i class="fa-solid fa-plus"></i></strong> pour en ajouter un.
        </div>
        <div class="row mb-4 mt-2 g-3" v-else>
            <article class="col-sm-6 col-md-4" v-for="synopsis in synopsisStore.synopses" :key="synopsis.id">
                <div class="bg-light shadow p-3 position-relative h-100 d-flex flex-column">
                    <header>
                        <span v-for="category in synopsis.categories" :key="category.id" class="badge me-1 bg-secondary">
                            {{ category.title }}
                        </span>
                        <h2 class="h5 mb-0">{{ synopsis.title }}</h2>
                        <p class="small mb-3"> {{ formatDatetime(synopsis.createdAt) }} </p>
                    </header>
                    <p class="pitch flex-fill">
                        {{ synopsis.pitch }}
                    </p>
                    <footer class="border-top pt-2">
                        <router-link :to="{ name: 'SynopsisShow', params:{slug: synopsis.slug, id: synopsis.id} }" class="text-decoration-none stretched-link">
                            <span class="text-decoration-underline">Voir plus</span> <i class="fa-solid fa-chevron-right fa-fw"></i>
                        </router-link>
                    </footer>
                </div>
            </article>
        </div>
    </div>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import dayjs from 'dayjs';
import 'dayjs/locale/fr';
dayjs.locale('fr');

export default {
    name: 'SynopsisList',

    computed: {
        ...mapStores(useSynopsisStore),

        isEmpty() {
            return this.synopsisStore.synopses.length === 0 && this.synopsisStore.loading === false;
        },
    },

    methods: {
        formatDatetime(datetime) {
            return dayjs(datetime).format('DD MMMM YYYY')
        }
    },
}
</script>

<style scoped>
.pitch {
    white-space: pre-wrap;
}
</style>