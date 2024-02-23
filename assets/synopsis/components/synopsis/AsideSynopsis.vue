<template>
    <aside>
        <label for="search-input" class="fw-bold fs-6">Filtrer par mot clé</label>
        <input type="text" id="search-input" class="form-control input-sm" v-model="query" placeholder="rechercher...">

        <h3 class="fw-bold fs-6 mt-3">Filtrer par catégorie</h3>
        <select name="selectedCategories" id="selectedCategories" v-model="selectedCategories" multiple></select>
    </aside>
</template>

<script>
import { useCategoryStore } from '&synopsis/stores/category.js';
import { mapStores } from "pinia";
import { createToastify } from '&utils/toastify.js';
import 'choices.js/public/assets/styles/choices.css';
import Choices from 'choices.js';

export default {
    name: 'AsideSynopsis',

    props: {
        filters: {
            default: {},
            type: Object
        },
    },

    data() {
        return {
            query: null,
            selectedCategories: []
        }
    },

    computed: {
        ...mapStores(useCategoryStore),
    },

    watch: {
        query() {
            this.$emit('update:filters', {query: this.query, selectedCategories: this.selectedCategories});
            this.onChangeFilters(true);
        },

        selectedCategories() {
            this.$emit('update:filters', {query: this.query, selectedCategories: this.selectedCategories});
            this.onChangeFilters(true);
        },
    },

    async mounted () {
        this.$emit('on-loading', true);
        const status = await this.categoryStore.getCategories();
        if (!status) {
            createToastify("Le chargement des catégories a échoué", 'error');
        }

        this.initChoice();
        this.$emit('update:filters', {query: this.query, selectedCategories: this.selectedCategories});
        this.onChangeFilters(true);
    },

    methods: {
        initChoice() {
            const choice = new Choices('#selectedCategories', {
                allowHTML: true,
                loadingText: 'Chargement...',
                removeItemButton: true,
                noResultsText: 'Aucun résultat',
                noChoicesText: 'Aucun élément',
                itemSelectText: 'Cliquer pour sélectionner',
            });

            const options = [];
            this.categoryStore.categories.forEach(element => {
                options.push({value: element.id, label: element.title, selected: false});
            });
            choice.clearChoices();
            choice.setChoices(options);
        },

        onChangeFilters(debounce = false, time = 500) {
            const filters = {query: this.query, selectedCategories: this.selectedCategories};

            if (debounce) {
                clearTimeout(this.debounce);
                this.debounce = setTimeout(() => {
                    this.$emit("on-change", filters);
                }, time);
            } else {
                this.$emit("on-change", filters);
            }
        }
    },
}
</script>