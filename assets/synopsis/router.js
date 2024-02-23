import { createRouter, createWebHashHistory } from "vue-router";
import SynopsisIndex from "&synopsis/views/SynopsisIndex.vue";
import CategoryIndex from "&synopsis/views/CategoryIndex.vue";
import SynopsisShow from "&synopsis/views/SynopsisShow.vue";
SynopsisShow
const routes =  [
    {
        path: "/synopsis",
        name: "SynopsisIndex",
        component: SynopsisIndex
    },
    {
        path: "/synopsis/:slug-:id",
        name: "SynopsisShow",
        component: SynopsisShow
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