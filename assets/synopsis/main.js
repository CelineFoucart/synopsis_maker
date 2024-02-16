import { createApp } from 'vue';
import Synopsis from '&synopsis/Synopsis.vue'
import router from '&synopsis/router.js';
import { createPinia } from 'pinia';
import "toastify-js/src/toastify.css";
import 'floating-vue/dist/style.css';
import FloatingVue from 'floating-vue'

const pinia = createPinia()

createApp(Synopsis).use(FloatingVue).use(router).use(pinia).mount('#app')