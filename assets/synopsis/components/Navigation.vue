<template>
    <nav class="shadow-sm navbar-container">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <router-link :to="{ name: 'SynopsisIndex' }" class="nav-link py-2" :class="{'active' : isSynopsisRoute}">
                    <i class="fa-solid fa-book fa-fw"></i>
                    <span class="d-none d-md-inline ps-1">Synopsis</span>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'CharacterIndex' }" class="nav-link py-2">
                    <i class="fas fa-user fa-fw"></i>
                    <span class="d-none d-md-inline ps-1">Personnages</span>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'PlaceIndex' }" class="nav-link py-2">
                    <i class="fa-solid fa-location-dot fa-fw"></i>
                    <span class="d-none d-md-inline ps-1">Lieux</span>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'ArticleIndex' }" class="nav-link py-2">
                    <i class="fas fa-file fa-fw"></i>
                    <span class="d-none d-md-inline ps-1">Articles</span>
                </router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'Settings' }" class="nav-link py-2">
                    <i class="fa-solid fa-gears fa-fw"></i>
                    <span class="d-none d-md-inline ps-1">Configuration</span>
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
.navbar-container {
    font-size: 0.90rem;
}

.nav-pills .nav-link {
    border-radius: 0;
}

.nav-pills .nav-link.active, .nav-pills .show>.nav-link {
    background-color: rgb(233, 236, 239);
    color: inherit;
}

.nav-link {
    color: inherit;
}
</style>