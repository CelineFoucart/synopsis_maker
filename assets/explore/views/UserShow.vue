<template>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'index' }">Explorer</router-link>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ author ? author.username : 'Auteur' }}
            </li>
        </ol>
    </nav>
    <Error v-if="error"></Error>
    <article class="row" v-if="error === false && author !== null">
        <div class="col-md-4">
            <header class="card h-100 shadow-sm text-center">
                <div class="card-body">
                    <div><i class="fas fa-user-circle fa-6x"></i></div>
                    <h2 class="h5 mt-2">{{ author.username }}</h2>
                    <p class="text-muted small text-center mb-0">Inscription le {{ formatDatetime(author.createdAt) }}</p>
                </div>
            </header>
        </div>
        <div class="col-md-8">
            <section class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 pb-2 border-bottom card-title mb-0">Profil</h2>
                    <table class="table table-striped mb-0">
                        <tbody>
                            <tr>
                                <th>Rang</th>
                                <td>{{ author.profile.rank }}</td>
                            </tr>
                            <tr>
                                <th>Localisation</th>
                                <td>{{ author.profile.localisation }}</td>
                            </tr>
                            <tr>
                                <th>Centres d'intérêt</th>
                                <td>{{ author.profile.interests }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
        <div class="col-md-12">
            <section class="card shadow-sm mt-4">
                <div class="card-body">
                    <h2 class="h5 pb-2 border-bottom card-title">A propos</h2>
                    <div v-html="author.profile.about"></div>
                </div>
            </section>
        </div>
        <div class="col-md-12">
            <section class="card shadow-sm mt-4">
                <div class="card-body">
                    <h2 class="h5 pb-2 border-bottom card-title">Ses synopsis</h2>
                    <p v-if="author.synopses.length === 0" class="text-muted">Aucun synopsis publié</p>
                    <div class="row g-3">
                        <div class="col-md-6" v-for="synopsis in author.synopses" :key="synopsis.id">
                            <SynopsisCard :synopsis="synopsis" />
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </article>
</template>

<script>
import { mapStores } from "pinia";
import { useUserStore } from '&explore/stores/user.js';
import SynopsisCard from '&explore/components/SynopsisCard.vue';
import { createToastify } from '&utils/toastify.js';
import Error from '&utils/Error.vue';
import dayjs from 'dayjs';
import 'dayjs/locale/fr';
dayjs.locale('fr');

export default {
    name: 'UserShow',

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
        ...mapStores(useUserStore),

        author() {
            return this.userStore.user;
        }
    },

    async mounted () {
        const status = await this.userStore.getUser(this.$route.params.id);

        if (!status) {
            createToastify("Ce auteur n'existe pas.", 'error');
            this.error = true;
        }
    },

    methods: {
        formatDatetime(datetime) {
            return dayjs(datetime).format('DD MMMM YYYY HH:mm');
        }
    },
}
</script>