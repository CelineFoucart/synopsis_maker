import { createApp } from 'vue';
import Synopsis from '&synopsis/Synopsis.vue'
import router from '&synopsis/router.js';
import { createPinia } from 'pinia';

const pinia = createPinia()

createApp(Synopsis).use(router).use(pinia).mount('#app')