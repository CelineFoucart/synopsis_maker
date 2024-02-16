import { createRouter, createWebHashHistory } from "vue-router";
import SynopsisIndex from "&synopsis/views/SynopsisIndex.vue";

const routes =  [
    {
        path: "/",
        name: "SynopsisIndex",
        component: SynopsisIndex
    }
];

export default createRouter({
    routes: routes,
    history: createWebHashHistory()
});