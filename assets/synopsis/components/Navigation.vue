<template>
    <nav class="bg-success-subtle shadow-sm">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <router-link :to="{ name: 'SynopsisIndex' }" class="nav-link py-3" :class="{'active' : isSynopsisRoute}">
                    <i class="fa-solid fa-book fa-fw"></i>
                    <span class="d-none d-md-inline">Synopsis</span>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'CategoryIndex' }" class="nav-link py-3">
                    <i class="fa-solid fa-tags fa-fw"></i>
                    <span class="d-none d-md-inline">Catégories</span>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'CharacterIndex' }" class="nav-link py-3">
                    <i class="fas fa-user fa-fw"></i>
                    <span class="d-none d-md-inline">Personnages</span>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'PlaceIndex' }" class="nav-link py-3">
                    <i class="fa-solid fa-location-dot fa-fw"></i>
                    <span class="d-none d-md-inline">Lieux</span>
                </router-link>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        name: 'Navigation',

        computed: {
            isSynopsisRoute() {
                if (this.$route.name === undefined) {
                    return false;
                }

                return this.$route.path.startsWith('/synopsis');
            }
        },
    }
</script>

<style lang="css" scoped>
.nav-pills .nav-link {
    border-radius: 0;
}

.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
    background-color: #343a40;
    color: white;
}

.nav-link {
    color: inherit;
}
</style>