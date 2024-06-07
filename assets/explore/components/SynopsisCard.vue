<template>
    <section class="shadow-sm">
        <header class="p-3 bg-dark position-relative">
            <div class="row align-items-center">
                <div class="col-9">
                    <span v-for="category in synopsis.categories" :key="category.id" class="badge me-1 bg-danger">
                        {{ category.title }}
                    </span>
                    <h3 class="h5 text-white mt-2">{{ synopsis.title }}</h3>
                </div>
                <div class="col-3 text-end" v-if="showLink">
                    <router-link :to="{ name: 'synopsis_show', params:{slug: synopsis.slug, id: synopsis.id} }" class="text-white stretched-link">
                        <i class="fas fa-eye fa-2xl"></i>
                        <div class="visually-hidden">Voir</div>
                    </router-link>
                </div>
            </div>
        </header>
        <div class="p-3">
            <p class="text-secondary small">
                <span><i class="fas fa-calendar-alt fa-fw me-1"></i>{{ formatDatetime(synopsis.createdAt) }}</span>
                <span class="ps-2">
                    <i class="fas fa-user fa-fw me-1"></i> 
                    <router-link :to="{ name: 'user_show', params:{id: synopsis.author.id} }" class="text-decoration-none">
                        {{ synopsis.author.username }}
                    </router-link>
                </span>
            </p>
            <p class="pitch mb-0">
                {{ synopsis.pitch }}
            </p>
        </div>
    </section>
</template>

<script>
import dayjs from 'dayjs';
import 'dayjs/locale/fr';
dayjs.locale('fr');

export default {
    name: 'SynopsisCard',

    props: {
        synopsis: Object,
        showLink: {
            type: Boolean, 
            default: true
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