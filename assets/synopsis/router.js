import { createRouter, createWebHashHistory } from "vue-router";
import SynopsisIndex from "&synopsis/views/SynopsisIndex.vue";
import CategoryIndex from "&synopsis/views/CategoryIndex.vue";
import SynopsisShow from "&synopsis/views/SynopsisShow.vue";
import SynopsisEpisodes from "&synopsis/views/SynopsisEpisodes.vue";

const routes =  [
    { path: '/', redirect: '/synopsis' },
    {
        path: "/synopsis",
        children: [
            {
                path: "",
                name: "SynopsisIndex",
                component: SynopsisIndex,
            },
            {
                path: ':id-:slug',
                component: SynopsisShow,
                name: "SynopsisShow",
            },
            {
                path: ':id-:slug/episodes',
                component: SynopsisEpisodes,
                name: "SynopsisEpisodes",
            },
        ]
    },
    {
        path: "/category",
        name: "CategoryIndex",
        component: CategoryIndex
    }
];

export default createRouter({
    routes: routes,
    linkActiveClass: 'active',
    linkExactActiveClass: 'active',
    history: createWebHashHistory()
});