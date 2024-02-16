<template>
    <div class="card position-relative m-5">
        <div class="card-header pb-0">
            <Navigation></Navigation>
        </div>
        <div class="card-body">
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
