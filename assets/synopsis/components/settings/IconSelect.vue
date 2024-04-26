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
                                <div class="btn-icon" :class="{active: `${iconStyle} ${icon}` === selected}" @click="onSelect(icon)">
                                    <i :class="`${iconStyle} ${icon} ${iconClass}`"></i>
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
                                    par exemple pour <i class="fa-solid fa-map-marker"></i>, <em>fa-map-marker</em>.
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
                iconStyle: 'fa-solid',
                iconClass: 'fa-fw fa-3x',
                icons: [
                    'fa-file',
                    'fa-user',
                    'fa-person',
                    'fa-person-dress',
                    'fa-people-roof',
                    'fa-child',
                    'fa-coins',
                    'fa-scale-balanced',
                    'fa-hands-praying',
                    'fa-map',
                    'fa-masks-theater',
                    'fa-place-of-worship',
                    'fa-monument',
                    'fa-shop',
                    'fa-basketball',
                    'fa-flask',
                    'fa-seedling',
                    'fa-rocket',
                    'fa-robot',
                    'fa-wand-magic-sparkles',
                    'fa-dragon',
                    'fa-dungeon',
                    'fa-hand-fist',
                    'fa-shield',
                    'fa-fire',
                    'fa-book',
                    'fa-chess',
                    'fa-tree',
                    'fa-landmark',
                    'fa-feather',
                    'fa-building',
                    'fa-water',
                    'fa-sitemap',
                    'fa-crown',
                    'fa-route',
                    'fa-mountain',
                    'fa-kitchen-set',
                    'fa-hat-wizard',
                    'fa-gem',
                    'fa-dog',
                    'fa-cloud-sun-rain',
                    'fa-flag'
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
                this.$emit('on-update', this.iconStyle + ' ' + icon);
            },

            onCustomIcon() {
                this.$emit('on-update', this.iconStyle + ' ' + this.customIcon);
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