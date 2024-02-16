import { createRouter, createWebHashHistory } from "vue-router";
import SynopsisIndex from "&synopsis/views/SynopsisIndex.vue";
import CategoryIndex from "&synopsis/views/CategoryIndex.vue";

const routes =  [
    {
        path: "/",
        name: "SynopsisIndex",
        component: SynopsisIndex
    },
    {
        path: "/category",
        name: "CategoryIndex",
        component: CategoryIndex
    }
];

export default createRouter({
    routes: routes,
    history: createWebHashHistory()
});