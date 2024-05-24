<template>
    <button type="button" class="btn btn-link nav-link px-1" @click="toggleTheme">
        <i class="fa-solid fa-sun" v-if="theme === 'light'"></i>
        <i class="fa-solid fa-moon" v-else></i>
        <span class="visually-hidden">Changer le thème</span>
    </button>
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
        let currentTheme = this.getStoredTheme();
        if (null === currentTheme) {
            currentTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
            this.setStoredTheme(currentTheme);
        }
        this.theme = currentTheme;
        this.setDocumentTheme();
    },

    methods: {
        getStoredTheme() {
            return localStorage.getItem('theme');
        },

        setStoredTheme(theme) {
            localStorage.setItem('theme', theme);
        },

        setDocumentTheme() {
            document.documentElement.setAttribute('data-bs-theme', this.theme)
        },

        toggleTheme() {
            this.theme = this.theme === 'light' ? 'dark' : 'light';
            this.setStoredTheme(this.theme);
            this.setDocumentTheme();
        }
    },
}
</script>

<style scoped>

</style>