<template>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <router-link :to="{ name: 'index' }">Explorer</router-link>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                {{ synopsis ? synopsis.title : 'Synopsis' }}
            </li>
        </ol>
    </nav>

    <Error v-if="error"></Error>
    <article v-if="error === false && synopsis !== null" >
        <SynopsisCard :synopsis="synopsis" :showLink="false"></SynopsisCard>
        
        <section id="content"  v-if="synopsis.settings.showContent && synopsis.episodes.length > 0" class="card mt-3">
            <header class="card-header">
                <div class="float-end">
                    <button class="btn p-0" @click="showContent = !showContent">
                        <i class="fas fa-minus fa-fw" v-if="showContent"></i>
                        <i class="fas fa-plus fa-fw" v-else></i>
                    </button>
                </div>
                <h3 class="card-title h5 mb-0">Déroulé</h3>
            </header>
            <div class="card-body d-flex flex-column gap-3" v-if="showContent">
                <div v-html="synopsis.description"></div>

                <div v-for="chapter in synopsis.chapters" :key="chapter.id" class="rounded shadow-chapter p-3">
                    <div class="text-muted small">{{ chapter.title }}</div>
                    <div class="small" v-if="chapter.episodes.length < 1">
                        Aucun contenu n'est associé à cet élément.
                    </div>
                    <EpisodeCard :episode="episode" v-for="episode in chapter.episodes" :key="episode.id"></EpisodeCard>
                </div>
                <div class="rounded shadow-chapter p-3">
                    <div class="text-muted small">Sans chapitre</div>
                    <div v-for="episode in synopsis.episodes" :key="episode.id">
                        <EpisodeCard :episode="episode" v-if="episode.chapterId === null"></EpisodeCard>
                    </div>
                </div>
            </div>
        </section>
        
        <section id="characters" v-if="synopsis.settings.showCharacters && synopsis.characters.length > 0" class="card mt-3">
            <header class="card-header">
                <div class="float-end">
                    <button class="btn p-0" @click="showCharacters = !showCharacters">
                        <i class="fas fa-minus fa-fw" v-if="showCharacters"></i>
                        <i class="fas fa-plus fa-fw" v-else></i>
                    </button>
                </div>
                <h3 class="card-title h5 mb-0">Personnages</h3>
            </header>
            <div class="card-body" v-if="showCharacters">
                <div v-for="character in synopsis.characters" :key="character.id">
                    <h4 class="fs-6 border text-center py-1 my-2 bg-light">{{ character.name }}</h4>
                    <p class="pitch">{{ character.description }}</p>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="bg-light">Nom</th>
                                <td>{{ character.name }}</td>
                            </tr>
                            <tr v-if="character.birthday">
                                <th class="bg-light">Naissance</th>
                                <td>{{ character.birthday }}</td>
                            </tr>
                            <tr v-if="character.birthdayPlace">
                                <th class="bg-light">Lieu de naissance</th>
                                <td>{{ character.birthdayPlace }}</td>
                            </tr>
                            <tr v-if="character.deathDate">
                                <th class="bg-light">Mort</th>
                                <td>{{ character.deathDate }}</td>
                            </tr>
                            <tr v-if="character.deathPlace">
                                <th class="bg-light">Lieu de mort</th>
                                <td>{{ character.deathPlace }}</td>
                            </tr>
                            <tr v-if="character.species">
                                <th class="bg-light">Espèce</th>
                                <td>{{ character.species }}</td>
                            </tr>
                            <tr v-if="character.gender">
                                <th class="bg-light">Genre</th>
                                <td>{{ character.gender }}</td>
                            </tr>
                            <tr v-if="character.nationality">
                                <th class="bg-light">Nationalité</th>
                                <td>{{ character.nationality }}</td>
                            </tr>
                            <tr v-if="character.job">
                                <th class="bg-light">Emploi</th>
                                <td>{{ character.job }}</td>
                            </tr>
                            <tr v-if="character.faction">
                                <th class="bg-light">Faction</th>
                                <td>{{ character.faction }}</td>
                            </tr>
                            <tr v-if="character.membership">
                                <th class="bg-light">Affiliation</th>
                                <td>{{ character.membership }}</td>
                            </tr>
                            <tr v-if="character.parents || character.children || character.siblings || character.partner">
                                <th class="bg-light">Relations</th>
                                <td>
                                    <ul>
                                        <li v-if="character.parents"><strong>Parents :</strong> {{ character.parents }}</li>

                                        <li v-if="character.partner"><strong>Conjoint(e) :</strong> {{ character.partner }}</li>

                                        <li v-if="character.children"><strong>Enfants :</strong> {{ character.children }}</li>
                                    </ul>
                                </td>
                            </tr>
                            <tr v-if="character.appearance !== null && character.appearance.length > 0">
                                <th class="bg-light">Apparence</th>
                                <td v-html="character.appearance"></td>
                            </tr>
                            <tr v-if="character.biography !== null && character.biography.length > 0">
                                <th class="bg-light">Biographie</th>
                                <td v-html="character.biography"></td>
                            </tr>
                            <tr v-if="character.personality.length > 0 && character.personality[0].key != null">
                                <th class="bg-light">Personnalité</th>
                                <td>
                                    <ul>
                                        <li v-for="line in character.personality">
                                            <strong>{{ line.key }} :</strong> {{ line.content }}
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <p v-if="character.complementary" class="mb-0">
                        <strong>Informations complémentaires :</strong> <span class="pitch">{{ character.complementary }}</span>
                    </p>
                    <p v-if="character.link" class="mb-0"><strong>Voir aussi :</strong> <a :href="character.link">{{ character.link }}</a></p>
                </div>
            </div>
        </section>
        
        <section id="places" v-if="synopsis.settings.showPlaces  && synopsis.places.length > 0" class="card mt-3">
            <header class="card-header">
                <div class="float-end">
                    <button class="btn p-0" @click="showPlaces = !showPlaces">
                        <i class="fas fa-minus fa-fw" v-if="showPlaces"></i>
                        <i class="fas fa-plus fa-fw" v-else></i>
                    </button>
                </div>
                <h3 class="card-title h5 mb-0">Lieux</h3>
            </header>
            <div class="card-body" v-if="showPlaces">
                <div v-for="place in synopsis.places" :key="place.id">
                    <h4 class="fs-6 border text-center py-1 my-2 bg-light">{{ place.title }}</h4>
                    <p class="pitch">{{ place.role }}</p>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th class="bg-light">Rôle</th>
                                <td class="pitch">{{ place.role }}</td>
                            </tr>
                            <tr>
                                <th class="bg-light">Description</th>
                                <td v-html="place.description"></td>
                            </tr>
                        </tbody>
                    </table>
                    <p v-if="place.complementary" class="mb-0">
                        <strong>Informations complémentaires :</strong> <span class="pitch">{{ place.complementary }}</span>
                    </p>
                    <p v-if="place.link" class="mb-0"><strong>Voir aussi :</strong> <a :href="place.link">{{ place.link }}</a></p>

                </div>
            </div>
        </section>
    </article>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&explore/stores/synopsis.js';
import SynopsisCard from '&explore/components/SynopsisCard.vue';
import EpisodeCard from '&explore/components/EpisodeCard.vue';
import { createToastify } from '&utils/toastify.js';

export default {
    name: 'Synopsis',

    components: {
        Error,
        SynopsisCard,
        EpisodeCard
    },

    data() {
        return {
            error: false,
            showContent: true,
            showCharacters: true,
            showPlaces: true
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),

        synopsis() {
            return this.synopsisStore.synopsis;
        }
    },

    async mounted () {
        const status = await this.synopsisStore.getSynopsis(this.$route.params);

        if (!status) {
            createToastify("Ce synopsis n'existe pas.", 'error');
            this.error = true;
        }
    },
}
</script>

<style>
.card .bg-light {
    background: var(--bs-card-cap-bg) !important;
}

.card .bg-light.border {
    border-color: var(--bs-card-border-color) !important;
}

.shadow-chapter {
    box-shadow: 0 0rem 0.6rem rgba(0, 0, 0, 0.15);
}

.card th {
    width: 200px;
}
</style>
