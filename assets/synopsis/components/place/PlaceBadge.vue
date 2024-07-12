<template>
    <span class="badge text-bg-secondary me-1" @click="openEditModal = true">{{ place.title }}</span>
    <ShowPlaceModal :place="place" v-if="openEditModal" @on-close="openEditModal = false"></ShowPlaceModal>
</template>

<script>
import ShowPlaceModal from '&synopsis/components/place/ShowPlaceModal.vue';

export default {
    name: 'PlaceBadge',

    components: {
        ShowPlaceModal,
    },

    props: {
        place: Object,
    },

    data() {
        return {
            openEditModal: false,
        }
    },
}
</script>

<style scoped>
.badge {
    cursor: pointer;
}
</style>