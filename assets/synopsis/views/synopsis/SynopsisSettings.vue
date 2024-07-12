<template>
    <Error v-if="error"></Error>
    <article v-if="synopsisStore.synopsis !== null">
        <HeaderSynopsis :synopsis="synopsisStore.synopsis"></HeaderSynopsis>
        <div class="row mt-4 g-3">
            <div class="col-md-12 text-end">
                <button type="button" class="btn btn-success btn-sm" @click.prevent="save">
                    <i class="fa-solid fa-spinner fa-spin" v-if="loading"></i>
                    <i class="fa-solid fa-floppy-disk" v-else></i>
                    Sauvegarder
                </button>
            </div>
            <div class="col-md-6">
                <section class="card">
                    <div class="card-header text-muted">
                        <h2 class="h5 mb-0 card-title">Réglages de l'export</h2>
                        <p class="mb-0 small">
                            Configurez les éléments que vous souhaitez voir apparaître dans le fichier PDF et le fichier word générés.
                        </p>
                    </div>
                    <div class="card-body">
                        <h3 class="h6 text-muted mb-0 fw-bold">Présentation</h3>
                        <p class="text-muted">Choisissez quelles informations du synopsis vous souhaitez ajouter à l'export.</p>
                        <ul class="list-group shadow-sm rounded-0">
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.appendPitch" type="checkbox" role="switch" id="appendPitch">
                                    <label class="form-check-label" for="appendPitch">Ajouter le pitch</label>
                                </div>
                            </li>
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.appendDescription" type="checkbox" role="switch" id="appendDescription">
                                    <label class="form-check-label" for="appendDescription">Ajouter la description</label>
                                </div>
                            </li>
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.appendCategories" type="checkbox" role="switch" id="appendCategories">
                                    <label class="form-check-label" for="appendCategories">Ajouter les catégories</label>
                                </div>
                            </li>
                        </ul>
                        <hr class="my-4">
                        <h3 class="h6 text-muted mb-0 mt-0 fw-bold">Conception</h3>
                        <p class="text-muted">Choisissez les éléments de conception du synopsis que vous souhaitez ajouter à l'export.</p>
                        <ul class="list-group shadow-sm rounded-0">
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.appendChapters" type="checkbox" role="switch" id="appendChapters">
                                    <label class="form-check-label" for="appendChapters">Ajouter les chapitres</label>
                                </div>
                            </li>
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.appendChapterTitles" type="checkbox" role="switch" id="appendChapterTitles">
                                    <label class="form-check-label" for="appendChapterTitles">Ajouter les titres des chapitres</label>
                                </div>
                                <div class="small">Les titres apparaîtront seulement si vous choisissez d'afficher les chapitres.</div>
                            </li>
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.appendEpisodes" type="checkbox" role="switch" id="appendEpisodes">
                                    <label class="form-check-label" for="appendEpisodes">Ajouter les épisodes</label>
                                </div>
                            </li>
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.appendEpisodeTitles" type="checkbox" role="switch" id="appendEpisodeTitles">
                                    <label class="form-check-label" for="appendEpisodeTitles">Ajouter les titres des épisodes</label>
                                </div>
                                <div class="small">Les titres apparaîtront seulement si vous choisissez d'afficher les épisodes.</div>
                            </li>
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.appendEpisodeCharacters" type="checkbox" role="switch" id="appendEpisodeCharacters">
                                    <label class="form-check-label" for="appendEpisodeCharacters">Ajouter les personnages des épisodes</label>
                                </div>
                                <div class="small">Les personnages apparaîtront seulement si vous choisissez d'afficher les épisodes.</div>
                            </li>
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.appendEpisodePlaces" type="checkbox" role="switch" id="appendEpisodePlaces">
                                    <label class="form-check-label" for="appendEpisodePlaces">Ajouter les lieux des épisodes</label>
                                </div>
                                <div class="small">Les lieux apparaîtront seulement si vous choisissez d'afficher les épisodes.</div>
                            </li>
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.appendNotes" type="checkbox" role="switch" id="appendNotes">
                                    <label class="form-check-label" for="appendNotes">Ajouter les prises de notes</label>
                                </div>
                            </li>
                        </ul>
                        <hr class="my-4">
                        <h3 class="h6 text-muted mb-0 mt-0 fw-bold">Univers et personnages</h3>
                        <p class="text-muted">Choisissez quels éléments de l'univers et des personnages vous souhaitez ajouter à l'export.</p>
                        <ul class="list-group shadow-sm rounded-0">
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.appendCharacters" type="checkbox" role="switch" id="appendCharacters">
                                    <label class="form-check-label" for="appendCharacters">Ajouter les personnages</label>
                                </div>
                            </li>
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.appendPlaces" type="checkbox" role="switch" id="appendPlaces">
                                    <label class="form-check-label" for="appendPlaces">Ajouter les lieux</label>
                                </div>
                            </li>
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.appendWorldBuildingHome" type="checkbox" role="switch" id="appendWorldBuildingHome">
                                    <label class="form-check-label" for="appendWorldBuildingHome">Ajouter la page d'accueil de l'encyclopédie</label>
                                </div>
                            </li>
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.appendArticles" type="checkbox" role="switch" id="appendArticles">
                                    <label class="form-check-label" for="appendArticles">Ajouter les articles</label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </section>
            </div>
            <div class="col-md-6">
                <section class="card">
                    <div class="card-header text-muted">
                        <h2 class="h5 mb-0 card-title">Partager le synopsis</h2>
                        <p class="mb-0 small">
                            Configurez l'affichage du synopsis sur votre dashboard et sur l'affichage public.
                        </p>
                    </div>
                    <div class="card-body">
                        <h3 class="h6 text-muted mb-0 fw-bold">Niveau de visibilité</h3>
                        <p class="text-muted">Indiquez si vous souhaitez rendre public le synopsis.</p>
                        <ul class="list-group shadow-sm rounded-0">
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.isPublic" type="checkbox" role="switch" id="isPublic">
                                    <label class="form-check-label" for="isPublic">Rendre le synopsis public</label>
                                </div>
                                <div class="small">Si vous cochez cette case, le synopsis aura une page publique accessible à chaque membre.</div>
                            </li>

                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.showContent" type="checkbox" role="switch" id="showContent">
                                    <label class="form-check-label" for="showContent">Afficher le contenu</label>
                                </div>
                                <div class="small">Définissez si le détail des chapitres et des épisodes doit apparaître sur la page publique.</div>
                            </li>
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.showCharacters" type="checkbox" role="switch" id="showCharacters">
                                    <label class="form-check-label" for="showCharacters">Afficher les personnages</label>
                                </div>
                                <div class="small">Définissez si la liste des personnages et leur description doivent apparaître sur la page publique.</div>
                            </li>
                            <li class="list-group-item bg-settings">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" v-model="settings.showPlaces" type="checkbox" role="switch" id="showPlaces">
                                    <label class="form-check-label" for="showPlaces">Afficher les lieux</label>
                                </div>
                                <div class="small">Définissez si la liste des lieux et leur description doivent apparaître sur la page publique.</div>
                            </li>
                        </ul>
                    </div>
                </section>
            </div>
        </div>
    </article>
    <Loading v-if="synopsisStore.loading"></Loading>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { createToastify } from '&utils/toastify.js';
import Error from '&utils/Error.vue';
import HeaderSynopsis from '&synopsis/components/synopsis_show/HeaderSynopsis.vue';
import Loading from '&utils/Loading.vue';

export default {
    name: 'SynopsisSettings',

    components: {
        HeaderSynopsis,
        Error,
        Loading,
    },

    data() {
        return {
            error: false,
            loading: false,
            settings: {
                appendPitch: true,
                appendDescription: true,
                appendCategories: true,
                appendChapters: true,
                appendChapterTitles: true,
                appendEpisodeTitles: true,
                appendEpisodeCharacters: true,
                appendEpisodePlaces: true,
                appendNotes: true,
                appendEpisodes: true,
                appendCharacters: true,
                appendPlaces: true,
                appendWorldBuildingHome: true,
                appendArticles: true,
                isPublic: false,
                showContent: false,
                showCharacters: false,
                showPlaces: false
            }
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),
    },

    async mounted () {
        this.status = await this.synopsisStore.getSynopsis(this.$route.params);
        if (!this.status) {
            createToastify("Ce synopsis n'existe pas.", 'error');
            this.error = true;
        }
        
        this.settings = this.synopsisStore.synopsis.settings;
    },

    methods: {
        async save() {
            this.loading = true;
            const status = await this.synopsisStore.putSynopsisSettings(this.settings, this.synopsisStore.synopsis.id);
            if (status) {
                createToastify("Les paramètres ont été enregistrés.", 'success');
            } else {
                createToastify("L'opération a échoué.", 'error');
            }
            this.loading = false;
        }
    },
}
</script>

<style scoped>
.bg-settings {
    background-color: var(--bs-card-cap-bg);
}

.form-check-label {
    font-weight: 500;
}
</style>
