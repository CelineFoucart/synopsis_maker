<template>
    <nav>
        <ul class="nav nav-tabs border-bottom-0">
            <li class="nav-item">
                <router-link :to="{ name: 'SynopsisIndex' }" active-class="active" class="nav-link">
                    Mes synopsis
                </router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'CategoryIndex' }" active-class="active" class="nav-link">
                    Mes catégories
                </router-link>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        name: 'Navigation'
    }
</script>

<style lang="css" scoped>
.nav-pills .nav-link {
    border-radius: 0;
}
</style>