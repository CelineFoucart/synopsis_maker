<template>
    <div class="accordion" id="accordionExample">
        <section class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    Modifier l'icône
                </button>
            </h2>
            <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <div class="container">
                        <div class="row text-center justify-content-between row-cols-auto gap-2">
                            <div class="col" v-for="icon in icons">
                                <div class="btn-icon" :class="{active: `${icon}` === selected}" @click="onSelect(icon)">
                                    <i :class="`${icon} ${iconClass}`"></i>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <hr>
                                <label for="icon-class" class="fw-bold mb-1">Entrer les classes d'un icône fontawesome</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="icon-class" v-model="customIcon">
                                    <button class="btn btn-success" :disabled="disabled" type="button" @click="onCustomIcon">Ajouter</button>
                                </div>
                                <div class="form-text">
                                    Entrer les classes d'un icône <a href="https://fontawesome.com/v5/search?o=r&amp;m=free" target="_blank">fontawesome</a>,
                                    par exemple pour <i class="fa-solid fa-map-marker"></i>, <em>fa-solid fa-map-marker</em>.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    export default {
        name: 'IconSelect',

        props: {
            selected: {
                type: String,
                default: null
            },
        },

        data() {
            return {
                iconClass: 'fa-fw fa-3x',
                icons: [
                    'fa-solid fa-file',
                    'fa-solid fa-user',
                    'fa-solid fa-person',
                    'fa-solid fa-person-dress',
                    'fa-solid fa-people-roof',
                    'fa-solid fa-child',
                    'fa-solid fa-coins',
                    'fa-solid fa-scale-balanced',
                    'fa-solid fa-hands-praying',
                    'fa-solid fa-map',
                    'fa-solid fa-masks-theater',
                    'fa-solid fa-place-of-worship',
                    'fa-solid fa-monument',
                    'fa-solid fa-shop',
                    'fa-solid fa-basketball',
                    'fa-solid fa-flask',
                    'fa-solid fa-seedling',
                    'fa-solid fa-rocket',
                    'fa-solid fa-robot',
                    'fa-solid fa-wand-magic-sparkles',
                    'fas fa-hand-sparkles',
                    'fa-solid fa-dragon',
                    'fa-solid fa-dungeon',
                    'fa-solid fa-hand-fist',
                    'fa-solid fa-shield',
                    'fa-solid fa-fire',
                    'fa-solid fa-book',
                    'fa-solid fa-chess',
                    'fa-solid fa-tree',
                    'fa-solid fa-landmark',
                    'fa-solid fa-feather',
                    'fa-solid fa-building',
                    'fas fa-city',
                    'fa-solid fa-water',
                    'fa-solid fa-sitemap',
                    'fa-solid fa-crown',
                    'fa-solid fa-route',
                    'fa-solid fa-mountain',
                    'fa-solid fa-kitchen-set',
                    'fa-solid fa-hat-wizard',
                    'fa-solid fa-gem',
                    'fas fa-horse',
                    'fa-solid fa-cloud-sun-rain',
                    'fa-solid fa-flag',
                    'fas fa-globe-europe',
                    'fas fa-globe',
                    'fas fa-star',
                    'fas fa-atlas'
                ],
                customIcon: null
            }
        },

        computed: {
            disabled() {
                return this.customIcon === null; 
            }
        },

        methods: {
            onSelect(icon) {
                this.$emit('on-update', icon);
            },

            onCustomIcon() {
                this.$emit('on-update', this.customIcon);
            }
        },
    }
</script>

<style scoped>
.btn-icon {
    cursor: pointer;
    border-radius: var(--bs-border-radius);
    border: 1px solid transparent;
    padding: 10px;
}
.btn-icon.active {
    border: 1px solid var(--bs-border-color);
    background: var(--bs-tertiary-bg);
}
</style>