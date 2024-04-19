<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="characterListModal" tabindex="-1" aria-labelledby="characterListModalLabel">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5" id="characterListModalLabel">Liste des personnages</h3>
                        <button type="button" class="btn-close" aria-label="fermeture" @click.prevent="closeModal"></button>
                    </div>
                    <div class="modal-body position-relative">
                        <div class="row g-2">
                            <div class="col-6" v-for="character in characters">
                                <section class="border bg-light p-1 button" @click="append(character.id)">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4 class="fs-6 mb-0">
                                            {{ character.name }}
                                        </h4>
                                        <span class="fs-6">
                                            <i class="fa-solid fa-plus fa-fw"></i> 
                                        </span>
                                    </div>
                                </section>
                            </div>
                        </div>
                        <Loading v-if="loading"></Loading>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { useCharacterStore } from '&synopsis/stores/character.js';
import { createToastify } from '&utils/toastify.js';
import Loading from '&utils/Loading.vue';

export default {
    name: 'CharacterListModal',

    components: {
        Loading,
    },

    props: {
        data: Object
    },

    data() {
        return {
            loading: false,
        }
    },

    computed: {
        ...mapStores(useSynopsisStore, useCharacterStore),

        characters() {
            const characters = [];

            this.characterStore.characters.forEach(character => {
                const index = this.synopsisStore.synopsis.characters.findIndex(element => element.id === character.id);
                if (index === -1) {
                    characters.push(character);
                }
            });

            return characters;
        }
    },

    async mounted () {
        this.loading = true;

        const status = await this.characterStore.getAll();
        if (!status) {
            createToastify('Le chargement a échoué', 'error');
        }
        
        this.loading = false;
    },

    methods: {
        closeModal() {
            this.$emit('on-close');
        },

        async append(characterId) {
            this.loading = true;

            const status = await this.synopsisStore.appendCharacterFromList(characterId);
            if (!status) {
                createToastify("L'ajout a échoué.", 'error');
            } else {
                createToastify("Le personnage a été ajouté.", 'success');
            }

            this.loading = false;
        },
    },
}
</script>

<style scoped>
#characterListModal {
    display: block;
    z-index: 3000;
}

.modal-body {
    min-height: 350px;
}
</style>