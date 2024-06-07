import { createApp } from 'vue';
import Explore from '&explore/Explore.vue';
import router from '&explore/router.js';
import { createPinia } from 'pinia';
import "toastify-js/src/toastify.css";
import 'floating-vue/dist/style.css';
import FloatingVue from 'floating-vue';

const pinia = createPinia()

createApp(Explore).use(FloatingVue).use(router).use(pinia).mount('#explore')