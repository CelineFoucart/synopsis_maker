<template>
    <div>
        <Navigation></Navigation>
        <div class="container-fluid my-4">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
import Navigation from '&synopsis/components/Navigation.vue'

export default {
    name: 'Synopsis',
    
    components: {
        Navigation,
    },
}
</script>
