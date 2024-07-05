<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="characterModal" tabindex="-1" aria-labelledby="characterModalLabel">
            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5" id="characterModalLabel">Personnage</h3>
                        <button type="button" class="btn-close" aria-label="fermeture" @click.prevent="closeModal"></button>
                    </div>
                    <div class="modal-body bg-light">
                        <h3 class="h5">
                            <a :href="character.link" v-if="character.link !== null && character.link.length > 0" target="_blank" class="text-decoration-none">
                                {{ character.name }}
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                            <span v-else>{{ character.name }}</span>
                        </h3>
                        <p class="pitch">{{ character.description }}</p>

                        <ul class="nav nav-tabs">
                            <li class="nav-item" @click.prevent="toggleTab('informations')">
                                <a class="nav-link" :class="{active: tabs.informations}" role="button">
                                    <i class="fa-solid fa-circle-info fa-fw"></i>
                                    <span class="d-none d-lg-inline">
                                        Informations
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item" @click.prevent="toggleTab('biography')">
                                <a class="nav-link" :class="{active: tabs.biography}" role="button">
                                    <i class="fa-solid fa-file-lines fa-fw"></i>
                                    <span class="d-none d-lg-inline">
                                        Biographie
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item" @click.prevent="toggleTab('appearance')">
                                <a class="nav-link" :class="{active: tabs.appearance}" role="button">
                                    <i class="fa-solid fa-address-card fa-fw"></i>
                                    <span class="d-none d-lg-inline">
                                        Apparence
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item" @click.prevent="toggleTab('personality')">
                                <a class="nav-link" :class="{active: tabs.personality}" role="button">
                                    <i class="fa-solid fa-icons fa-fw"></i>
                                    <span class="d-none d-lg-inline">
                                        Personnalité
                                    </span>
                                </a>
                            </li>
                            <li class="nav-item" @click.prevent="toggleTab('relations')">
                                <a class="nav-link" :class="{active: tabs.relations}" role="button">
                                    <i class="fa-solid fa-people-group fa-fw"></i>
                                    <span class="d-none d-lg-inline">
                                        Relations
                                    </span>
                                </a>
                            </li>
                        </ul>
                        <div class="mb-3 bg-white p-3 border-start border-end border-bottom">
                            <div v-if="tabs.informations">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Rôle dans l'histoire</th>
                                        <td class="pitch">{{ character.role }}</td>
                                    </tr>
                                    <tr>
                                        <th>Naissance</th>
                                        <td>{{ character.birthday }}</td>
                                    </tr>
                                    <tr>
                                        <th>Lieu de naissance</th>
                                        <td>{{ character.birthdayPlace }}</td>
                                    </tr>
                                    <tr>
                                        <th>Mort</th>
                                        <td>{{ character.deathDate }}</td>
                                    </tr>
                                    <tr>
                                        <th>Lieu de mort</th>
                                        <td>{{ character.deathPlace }}</td>
                                    </tr>
                                    <tr>
                                        <th>Espèce</th>
                                        <td>{{ character.species }}</td>
                                    </tr>
                                    <tr>
                                        <th>Genre</th>
                                        <td>{{ character.gender }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nationalité</th>
                                        <td>{{ character.nationality }}</td>
                                    </tr>
                                    <tr>
                                        <th>Emploi</th>
                                        <td>{{ character.job }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div v-if="tabs.biography">
                                <label for="biography" class="d-lg-none fw-bold">Biographie</label>
                                <Description id="biography" v-model:data="character.biography" :editMode="false"></Description>
                            </div>
                            
                            <div v-if="tabs.appearance">
                                <label for="appearance" class="d-lg-none fw-bold">Apparence</label>
                                <Description id="appearance" v-model:data="character.appearance" :editMode="false"></Description>
                            </div>
                            
                            <div v-if="tabs.personality">
                                <table class="table table-bordered" v-if="character.personality.length > 0 && character.personality[0].key != null">
                                    <tr v-for="itemPersonality in character.personality" :key="itemPersonality.id">
                                        <th>{{ itemPersonality.key }}</th>
                                        <td>{{ itemPersonality.content }}</td>
                                    </tr>
                                </table>
                            </div>

                            <div v-if="tabs.relations">
                                <table class="table table-bordered">
                                    <tr>
                                        <th>Parents</th>
                                        <td>{{ character.parents }}</td>
                                    </tr>
                                    <tr>
                                        <th>Conjoint(e)</th>
                                        <td>{{ character.partner }}</td>
                                    </tr>
                                    <tr>
                                        <th>Enfants</th>
                                        <td>{{ character.children }}</td>
                                    </tr>
                                    <tr>
                                        <th>Faction</th>
                                        <td>{{ character.faction }}</td>
                                    </tr>
                                    <tr>
                                        <th>Affiliation</th>
                                        <td>{{ character.membership }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Description from '&utils/Description.vue';

export default {
    name: 'ShowCharacterModal',

    components: {
        Description,
    },

    props: {
        character: Object,
    },

    data() {
        return {
            tabs: { biography: false, appearance: false, personality: false, informations: true, relations: false }
        }
    },

    methods: {
        closeModal() {
            this.$emit('on-close');
        },

        toggleTab(type) {
            this.tabs.biography = false;
            this.tabs.appearance = false;
            this.tabs.personality = false;
            this.tabs.informations = false;
            this.tabs.relations = false;
            this.tabs[type] = true;
        },
    },
}
</script>

<style scoped>
#characterModal {
    display: block;
}
</style>