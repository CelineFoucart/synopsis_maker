<template>
    <section class="card mt-4">
        <header class="card-header">
            <div class="row">
                <div class="col-8"><h2 class="h5 mb-0 card-title">Personnages associés</h2></div>
                <div class="col-4 text-end">
                    <button type="button" class="btn btn-sm btn-success me-1" v-tooltip="'Ajouter depuis la liste'" @click="characterListModalShow = true">
                        <i class="fa-solid fa-list fa-fw"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-success" v-tooltip="'Ajouter'" @click="openCharacterModal(null)">
                        <i class="fa-solid fa-plus fa-fw"></i>
                    </button>
                </div>
            </div>
        </header>
        <div class="card-body">
            <table class="table border mb-0 table-striped">
                <thead>
                    <tr>
                        <th>Personnage</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="character in synopsisStore.synopsis.characters" :key="character.id">
                        <td>
                            <h3 class="fw-bold h6 mb-0">
                                <a :href="character.link" v-if="character.link !== null" target="_blank" class="text-decoration-none">
                                    {{ character.name }}
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                                <span v-else>{{ character.name }}</span>
                            </h3>
                            <div style="white-space: pre-wrap;">{{ substract(character.description) }}</div>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-dark" v-tooltip="'Editer'" @click="openCharacterModal(character)">
                                    <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                    <span class="visually-hidden">Editer</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" v-tooltip="'Retirer'" @click="openUnlinkModal(character)">
                                    <i class="fa-solid fa-link-slash fa-fw"></i>
                                    <span class="visually-hidden">Retirer</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="synopsisStore.synopsis.characters.length === 0" class="text-center">
                        <td colspan="3">Aucun personnage associé</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <CharacterListModal @on-close="characterListModalShow = false" v-if="characterListModalShow"></CharacterListModal>
        <CharacterModal :data="character" @on-close="characterModalShow = false" v-if="characterModalShow"></CharacterModal>
        <UnlinkModal :loading="loading" :title="character.name" @on-confirm="unlinkElement" @on-close="unlinkModalShow = false" v-if="unlinkModalShow"></UnlinkModal>
    </section>
</template>

<script lang="js">
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { useCharacterStore } from '&synopsis/stores/character.js';
import CharacterModal from '&synopsis/components/character/CharacterModal.vue';
import UnlinkModal from '&utils/UnlinkModal.vue';
import { createToastify } from '&utils/toastify.js';
import CharacterListModal from '&synopsis/components/character/CharacterListModal.vue';

export default {
    name: 'SynopsisCharacter',

    components: {
        CharacterModal,
        UnlinkModal,
        CharacterListModal
    },

    data() {
        return {
            characterModalShow: false,
            unlinkModalShow: false,
            characterListModalShow: false,
            character: { name: null },
            loading: false
        }
    },

    computed: {
        ...mapStores(useSynopsisStore, useCharacterStore),
    },

    mounted () {
        this.resetCharacter();
    },

    methods: {
        resetCharacter() {
            this.character = {
                id: null, 
                name: null, 
                link: null, 
                description: null, 
                biography: '', 
                appearance: '', 
                personality: [ ]
            };
        },

        openCharacterModal(character = null) {
            if (character === null) {
                this.resetCharacter();
            } else {
                this.character = character;
                this.characterStore.setCharacters(this.synopsisStore.synopsis.characters);
            }

            this.characterModalShow = true;
        },
        
        openUnlinkModal(character) {
            this.character = character;
            this.unlinkModalShow = true;
        },

        substract(value) {
            const MAX_LENGTH = 150;

            if (value === null) {
                return '';
            }
            
            if (value.length <= MAX_LENGTH) {
                return value;
            }

            return value.substring(0, MAX_LENGTH) + '[...]';
        },

        async unlinkElement() {
            this.loading = true;

            let success = false;
            if (this.place !== null) {
                success = await this.synopsisStore.removeCharacter(this.character.id);
            }

            if (success) {
                createToastify("L'élément a été retiré du synopsis.", 'success');
                this.characterStore.setCharacters(this.synopsisStore.synopsis.characters);
            } else {
                createToastify("L'opération a échoué.", 'error');
            }

            this.unlinkModalShow = false;
            this.loading = false;
        }
    },
}
</script>