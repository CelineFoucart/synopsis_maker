<template>
    <span class="badge text-bg-secondary me-1" @click="openEditModal = true">{{ character.name }}</span>
    <ShowCharacterModal :character="character" v-if="openEditModal" @on-close="openEditModal = false"></ShowCharacterModal>
</template>

<script>
import ShowCharacterModal from '&synopsis/components/character/ShowCharacterModal.vue';

export default {
    name: 'CharacterBadge',

    components: {
        ShowCharacterModal,
    },

    props: {
        character: Object,
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