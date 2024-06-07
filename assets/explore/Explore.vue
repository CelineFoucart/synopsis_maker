<template>
    <header class="py-4 text-center shadow-sm">
        <h1 class="display-5">Explorer</h1>
        <p class="fs-5 text-secondary">Explorer les synopsis partagés par les autres membres.</p>
    </header>
    <div class="container-fluid my-3">
        <router-view></router-view>
    </div>
</template>

<script>
    export default {
        name: 'Explore'
    }
</script>

<style lang="css" scoped>

</style>