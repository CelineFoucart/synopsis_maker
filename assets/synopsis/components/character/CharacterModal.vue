<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="characterModal" tabindex="-1" aria-labelledby="characterModalLabel">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5" id="characterModalLabel">Personnage</h3>
                        <button type="button" class="btn-close" aria-label="fermeture" @click.prevent="closeModal"></button>
                    </div>
                    <div class="modal-body bg-light">
                        <div class="d-flex gap-2 mb-3">
                            <div class="rounded image-character">
                                <i class="fa-solid fa-circle-user fa-8x"></i>
                            </div>
                            <div class="flex-fill">
                                <div class="mb-3">
                                    <label for="name" class="form-label mb-0 required">Nom</label>
                                    <input type="text" class="form-control" id="name" v-model="name" :class="{ 'is-invalid': v$.name.$errors.length }">
                                    <div class="invalid-feedback">
                                        Ce champ est obligatoire et doit faire entre 2 et 255 caractères.
                                    </div>
                                </div>
                                <div>
                                    <label for="description" class="form-label mb-0">Description</label>
                                    <textarea class="form-control" id="description" v-model="description" :class="{ 'is-invalid': v$.description.$errors.length }"></textarea>
                                    <div class="invalid-feedback">
                                        Ce champ doit faire entre 5 et 15000 caractères.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <ul class="nav nav-tabs">
                            <li class="nav-item" @click.prevent="toggleTab('biography')">
                                <a class="nav-link" :class="{active: tabs.biography}" role="button">Biographie</a>
                            </li>
                            <li class="nav-item" @click.prevent="toggleTab('appearance')">
                                <a class="nav-link" :class="{active: tabs.appearance}" role="button">Apparence</a>
                            </li>
                            <li class="nav-item" @click.prevent="toggleTab('personality')">
                                <a class="nav-link" :class="{active: tabs.personality}" role="button">Personnalité</a>
                            </li>
                        </ul>

                        <div class="mb-3 bg-white p-3 border-start border-end border-bottom">
                            <div v-if="tabs.biography">
                                <label for="biography" class="visually-hidden">Biographie</label>
                                <Description v-model:data="biography" :saveButton="false"></Description>
                            </div>
                            
                            <div v-if="tabs.appearance">
                                <label for="appearance" class="visually-hidden">Apparence</label>
                                <Description v-model:data="appearance" :saveButton="false"></Description>
                            </div>
                            
                            <div v-if="tabs.personality">
                                <template v-for="personality in personality" :key="personality.id">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="text" v-model="personality.key" class="form-control" id="key" placeholder="Par exemple, but, motivation...">
                                                <label for="key">Titre</label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="text-end pb-1">
                                                <button type="button" class="btn btn-link p-0" @click="removePersonality(personality.id)">
                                                    <i class="fa-solid fa-trash text-danger fa-lg"></i>
                                                    <span class="visually-hidden">Retirer</span>
                                                </button>
                                            </div>
                                            <div class="form-floating">
                                                <textarea id="content" placeholder="Ajouter une description" v-model="personality.content" class="form-control"></textarea>
                                                <label for="content">Contenu</label>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                </template>
                                <button type="submit" class="btn btn-sm btn-success" @click="addPersonality">
                                    <i class="fa-solid fa-plus fa-fw"></i> Ajouter
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="link" class="form-label">Lien</label>
                            <input type="url" class="form-control" id="link" v-model="link" :class="{ 'is-invalid': v$.link.$errors.length }">
                            <div class="form-text">Ajoutez un lien vers un article ou une page de blog sur ce lieu.</div>
                            <div class="invalid-feedback">
                                Ce champ doit faire entre 10 et 255 caractères et est être une URL valide.
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" @click.prevent="closeModal">
                            <i class="fa-solid fa-xmark"></i>
                            Annuler
                        </button>
                        <button type="button" class="btn btn-success btn-sm" @click.prevent="save">
                            <i class="fa-solid fa-spinner fa-spin" v-if="loading"></i>
                            <i class="fa-solid fa-floppy-disk" v-else></i>
                            Sauvegarder
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="js">
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { useCharacterStore } from '&synopsis/stores/character.js';
import { createToastify } from '&utils/toastify.js';
import { required, maxLength, minLength, url  } from '@vuelidate/validators';
import { useVuelidate } from '@vuelidate/core';
import Description from '&utils/Description.vue';

export default {
    name: 'CharacterModal',

    components: {
        Description,
    },

    props: {
        data: Object
    },

    data() {
        return {
            name: null,
            link: null,
            description: null,
            biography: '',
            appearance: '',
            personality: [],
            v$: useVuelidate(),
            loading: false,
            tabs: { biography: true, appearance: false, personality: false }
        }
    },

    computed: {
        ...mapStores(useSynopsisStore, useCharacterStore),
    },

    validations () {
        return {
            name: { required, maxLength: maxLength(255), minLength: minLength(2) },
            link: { maxLength: maxLength(255), minLength: minLength(10), url },
            description: { maxLength: maxLength(2500), minLength: minLength(5) },
            biography: { maxLength: maxLength(25000), minLength: minLength(5) },
            appearance: { maxLength: maxLength(25000), minLength: minLength(5) },
        }
    },

    mounted () {
        this.name = this.data.name;
        this.link = this.data.link;
        this.description = this.data.description;
        this.biography = this.data.biography;
        this.appearance = this.data.appearance;
        this.personality = this.data.personality;

        if (this.personality.length === 0) {
            this.addPersonality();
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
            this.tabs[type] = true;
        },

        addPersonality() {
            this.personality.push({ id: Date.now(), key: null, content: null});
        },

        removePersonality(id) {
                const index = this.personality.findIndex((row) => row.id === id);
                if (index !== -1) {
                    this.personality.splice(index, 1);
                }
        },

        async save() {
            this.loading = true;
            const result = await this.v$.$validate();

            if (!result) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
                this.loading = false;
                return;
            }

            const data = {
                name: this.name, 
                link: this.link,
                description: this.description,
                biography: this.biography, 
                appearance: this.appearance, 
                personality: this.personality, 
            };

            let success = false;
            if (this.data.id) {
                success = await this.characterStore.edit(this.data.id, data);
            } else {
                success = await this.synopsisStore.addCharacter(data);
            }

            if (!success) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
                this.loading = false;
            } else {
                this.closeModal();
            }
        },
    },
}
</script>

<style scoped>
#characterModal {
    display: block;
    z-index: 3000;
}

.input-group-text {
    width: 105px;
}

.image-character {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    width: fit-content;
}
</style>