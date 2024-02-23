import { createRouter, createWebHashHistory } from "vue-router";
import SynopsisIndex from "&synopsis/views/SynopsisIndex.vue";
import CategoryIndex from "&synopsis/views/CategoryIndex.vue";
import SynopsisShow from "&synopsis/views/SynopsisShow.vue";
SynopsisShow
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
                path: ':slug-:id',
                component: SynopsisShow,
                name: "SynopsisShow",
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