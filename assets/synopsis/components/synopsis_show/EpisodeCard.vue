<template>
    <article class="card h-100" :style="{'border-color': episode.color ? episode.color : 'rgba(0, 0, 0, 0.176' }">
        <div class="card-body">
            <h3 class="card-title h5 fw-bold">
                <i class="fas fa-circle" :style="{color: episode.color ? episode.color : '#000' }"></i>
                {{ episode.title }}
                <i class="fas fa-check-circle text-success" aria-label="Validé" v-if="episode.valid"></i>
            </h3>
            <p style="white-space: pre-wrap;">{{ episode.description }}</p>
        </div>
    </article>
</template>

<script>
export default {
    name: 'EpisodeCard',

    props: {
        episode: Object,
    },
}
</script>

<style scoped>

</style>