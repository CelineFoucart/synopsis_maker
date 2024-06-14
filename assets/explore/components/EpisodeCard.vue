<template>
    <div>
        <h4 class="fs-6 border text-center py-1 bg-light">{{ episode.title }}</h4>
        <p v-if="episode.characters.length > 0" class="mb-0">
            <i class="fa-solid fa-user fa-fw"></i>
            <span v-for="character in episode.characters" class="badge text-bg-secondary me-1">{{ character.name }}</span>
        </p>
        <p v-if="episode.places.length > 0" class="mb-0">
            <i class="fa-solid fa-location-dot fa-fw"></i>
            <span v-for="place in episode.places" class="badge text-bg-secondary me-1">{{ place.title }}</span>
        </p>
        <div class="my-2" v-html="episode.content"></div>
        <div class="my-2 small" v-if="episode.content === null || episode.content.length < 1">
            Aucun contenu n'est associé à cet élément.
        </div>
    </div>
</template>

<script>
    export default {
        name: 'EpisodeCard',

        props: {
            episode: Object,
        },
    }
</script>
