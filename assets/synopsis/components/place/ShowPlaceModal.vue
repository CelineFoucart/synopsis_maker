<template>
    <div>
        <div class="modal-backdrop fade show"></div>
        <div class="modal fade show" id="placeModal" tabindex="-1" aria-labelledby="placeModalLabel">
            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title h5" id="placeModalLabel">Lieu</h3>
                        <button type="button" class="btn-close" aria-label="fermeture" @click.prevent="closeModal"></button>
                    </div>
                    <div class="modal-body bg-light">
                        <h3 class="modal-title h5" id="placeModalLabel">
                            <a :href="place.link" v-if="place.link !== null && place.link.length > 0" target="_blank" class="text-decoration-none">
                                {{ place.title }}
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                            <span v-else>{{ place.title }}</span>
                        </h3>
                        <p class="pitch">{{ place.role }}</p>
                        <div class="rounded border bg-white p-3">
                            <h4 class="h6">Description</h4>
                            <Description id="description" v-model:data="place.description" :editMode="false"></Description>
                            <div v-if="place.complementary !== null && place.complementary.length > 0">
                                <h4 class="h6">Informations complémentaires</h4>
                                <p class="pitch">{{ place.complementary }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script lang="js">
import Description from '&utils/Description.vue';

export default {
    name: 'ShowPlaceModal',

    components: {
        Description,
    },

    props: {
        place: Object
    },

    methods: {
        closeModal() {
            this.$emit('on-close');
        },
    },
}
</script>

<style scoped>
#placeModal {
    display: block;
}
</style>
