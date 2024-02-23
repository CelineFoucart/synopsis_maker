<template>
    <nav>
        <ul class="nav nav-tabs border-bottom-0">
            <li class="nav-item">
                <router-link :to="{ name: 'SynopsisIndex' }" class="nav-link" :class="{'active' : isSynopsisRoute}">
                    Mes synopsis
                </router-link>
            </li>
            <li class="nav-item">
                <router-link :to="{ name: 'CategoryIndex' }" class="nav-link">
                    Mes catégories
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
</style>