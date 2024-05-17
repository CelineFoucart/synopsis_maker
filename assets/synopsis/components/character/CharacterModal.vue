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
                        <div class="d-flex gap-2 mb-3">
                            <div class="rounded element-form-icon">
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
                            <div v-if="tabs.biography">
                                <label for="biography" class="d-lg-none fw-bold">Biographie</label>
                                <Description v-model:data="biography" :saveButton="false"></Description>
                            </div>
                            
                            <div v-if="tabs.appearance">
                                <label for="appearance" class="d-lg-none fw-bold">Apparence</label>
                                <Description v-model:data="appearance" :saveButton="false"></Description>
                            </div>
                            
                            <div v-if="tabs.personality">
                                <template v-for="itemPersonality in personality" :key="itemPersonality.id">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-12 text-end">
                                            <button type="button" class="btn btn-link p-0" @click="removePersonality(itemPersonality.id)">
                                                    <i class="fa-solid fa-trash text-danger fa-lg"></i>
                                                    <span class="visually-hidden">Retirer</span>
                                                </button>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-floating">
                                                <input type="text" v-model="itemPersonality.key" class="form-control" id="key" placeholder="Par exemple, but, motivation...">
                                                <label for="key">Titre</label>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-floating">
                                                <textarea id="content" placeholder="Ajouter une description" v-model="itemPersonality.content" class="form-control"></textarea>
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

                            <div v-if="tabs.informations">
                                <div class="row">
                                    <div class="col_md-12">
                                        <div class="mb-3">
                                            <label for="role" class="form-label fw-bold">Rôle dans l'histoire</label>
                                            <textarea class="form-control" id="role" v-model="role" :class="{ 'is-invalid': v$.role.$errors.length }"></textarea>
                                            <div class="invalid-feedback">
                                                Ce champ doit faire entre 1 et 15000 caractères.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="birthday" class="form-label fw-bold">Date de naissance</label>
                                            <input type="text" class="form-control" v-model="birthday" id="birthday" :class="{ 'is-invalid': v$.birthday.$errors.length }">
                                            <div class="invalid-feedback">
                                                Ce champ doit faire entre 1 et 255 caractères.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="birthdayPlace" class="form-label fw-bold">Lieu de naissance</label>
                                            <input type="text" class="form-control" v-model="birthdayPlace" id="birthdayPlace" :class="{ 'is-invalid': v$.birthdayPlace.$errors.length }">
                                            <div class="invalid-feedback">
                                                Ce champ doit faire entre 1 et 255 caractères.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="deathDate" class="form-label fw-bold">Date de mort</label>
                                            <input type="text" class="form-control" v-model="deathDate" id="deathDate" :class="{ 'is-invalid': v$.deathDate.$errors.length }">
                                            <div class="invalid-feedback">
                                                Ce champ doit faire entre 1 et 255 caractères.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="deathPlace" class="form-label fw-bold">Lieu de mort</label>
                                            <input type="text" class="form-control" v-model="deathPlace" id="birthdayPlace" :class="{ 'is-invalid': v$.deathPlace.$errors.length }">
                                            <div class="invalid-feedback">
                                                Ce champ doit faire entre 1 et 255 caractères.
                                            </div>
                                        </div>                                        
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="species" class="form-label fw-bold">Espèce</label>
                                            <input type="text" class="form-control" v-model="species" id="species" :class="{ 'is-invalid': v$.species.$errors.length }">
                                            <div class="invalid-feedback">
                                                Ce champ doit faire entre 1 et 255 caractères.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="gender" class="form-label fw-bold">Genre</label>
                                            <input type="text" class="form-control" v-model="gender" id="gender" :class="{ 'is-invalid': v$.gender.$errors.length }">
                                            <div class="invalid-feedback">
                                                Ce champ doit faire entre 1 et 255 caractères.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nationality" class="form-label fw-bold">Nationalité</label>
                                            <input type="text" class="form-control" v-model="nationality" id="nationality" :class="{ 'is-invalid': v$.nationality.$errors.length }">
                                            <div class="invalid-feedback">
                                                Ce champ doit faire entre 1 et 255 caractères.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="job" class="form-label fw-bold">Emploi</label>
                                            <input type="text" class="form-control" v-model="job" id="job" :class="{ 'is-invalid': v$.job.$errors.length }">
                                            <div class="invalid-feedback">
                                                Ce champ doit faire entre 1 et 255 caractères.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="complementary" class="form-label fw-bold">Informations complémentaires</label>
                                        <textarea class="form-control" id="complementary" v-model="complementary" :class="{ 'is-invalid': v$.complementary.$errors.length }"></textarea>
                                        <div class="invalid-feedback">
                                            Ce champ doit faire entre 1 et 15000 caractères.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-if="tabs.relations">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="parents" class="form-label fw-bold">Parents</label>
                                            <input type="text" class="form-control" v-model="parents" id="birthday" :class="{ 'is-invalid': v$.parents.$errors.length }">
                                            <div class="invalid-feedback">
                                                Ce champ doit faire entre 1 et 255 caractères.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="siblings" class="form-label fw-bold">Fratrie</label>
                                            <input type="text" class="form-control" v-model="siblings" id="birthday" :class="{ 'is-invalid': v$.siblings.$errors.length }">
                                            <div class="invalid-feedback">
                                                Ce champ doit faire entre 1 et 255 caractères.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="partner" class="form-label fw-bold">Conjoint(e)</label>
                                            <input type="text" class="form-control" v-model="partner" id="birthday" :class="{ 'is-invalid': v$.partner.$errors.length }">
                                            <div class="invalid-feedback">
                                                Ce champ doit faire entre 1 et 255 caractères.
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="children" class="form-label fw-bold">Enfants</label>
                                            <input type="text" class="form-control" v-model="children" id="birthday" :class="{ 'is-invalid': v$.children.$errors.length }">
                                            <div class="invalid-feedback">
                                                Ce champ doit faire entre 1 et 255 caractères.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="link" class="form-label">Lien</label>
                            <input type="url" class="form-control" id="link" v-model="link" :class="{ 'is-invalid': v$.link.$errors.length }">
                            <div class="form-text">Ajoutez un lien vers un article ou une page de blog sur ce personnage.</div>
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
            nationality: null,
            job: null,
            birthday: null,
            birthdayPlace: null,
            deathDate: null,
            deathPlace: null,
            parents: null,
            children: null,
            siblings: null,
            partner: null,
            species: null,
            gender: null,
            role: null,
            complementary: null,
            v$: useVuelidate(),
            loading: false,
            tabs: { biography: false, appearance: false, personality: false, informations: true, relations: false }
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
            nationality: { maxLength: maxLength(255), minLength: minLength(1) },
            job: { maxLength: maxLength(255), minLength: minLength(1) },
            birthday: { maxLength: maxLength(255), minLength: minLength(1) },
            birthdayPlace: { maxLength: maxLength(255), minLength: minLength(1) },
            deathDate: { maxLength: maxLength(255), minLength: minLength(1) },
            deathPlace: { maxLength: maxLength(255), minLength: minLength(1) },
            gender: { maxLength: maxLength(255), minLength: minLength(1) },
            species: { maxLength: maxLength(255), minLength: minLength(1) },
            role: { maxLength: maxLength(15000), minLength: minLength(1) },
            complementary: { maxLength: maxLength(15000), minLength: minLength(1) },

            parents: { maxLength: maxLength(255), minLength: minLength(1) },
            children: { maxLength: maxLength(255), minLength: minLength(1) },
            siblings: { maxLength: maxLength(255), minLength: minLength(1) },
            partner: { maxLength: maxLength(255), minLength: minLength(1) },
        }
    },

    mounted () {
        this.init();
    },

    methods: {
        init() {
            this.name = this.data.name;
            this.link = this.data.link;
            this.description = this.data.description;
            this.biography = this.data.biography;
            this.appearance = this.data.appearance;
            
            this.nationality = this.data.nationality;
            this.job = this.data.job;
            this.birthday = this.data.birthday;
            this.birthdayPlace = this.data.birthdayPlace;
            this.deathDate = this.data.deathDate;
            this.deathPlace = this.data.deathPlace;
            this.gender = this.data.gender;
            this.species = this.data.species;
            this.role = this.data.role;
            this.complementary = this.data.complementary;

            this.parents = this.data.parents;
            this.children = this.data.children;
            this.siblings = this.data.siblings;
            this.partner = this.data.partner;

            this.personality = this.data.personality;

            if (this.personality.length === 0) {
                this.addPersonality();
            }
        },

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
                nationality: this.nationality,
                job: this.job,
                birthday: this.birthday,
                birthdayPlace: this.birthdayPlace,
                deathDate: this.deathDate,
                deathPlace: this.deathPlace,
                gende: this.gender,
                species: this.species,
                role: this.role,
                complementary: this.complementary,
                parents: this.parents,
                children: this.children,
                siblings: this.siblings,
                partner: this.partner,
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
</style>