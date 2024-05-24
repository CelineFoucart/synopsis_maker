<template>
    <div>

    </div>
</template>

<script>
export default {
    name: 'DarkModeToggle',

    data() {
        return {
            theme: 'light'
        }
    },

    mounted () {
        ;
    },

    methods: {
        getStoredTheme() {
            return localStorage.getItem('theme');
        },

        setStoredTheme(theme) {
            localStorage.setItem('theme', theme);
        }
    },
}
</script>

<style scoped>

</style>