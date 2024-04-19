import { createRouter, createWebHashHistory } from "vue-router";
import SynopsisIndex from "&synopsis/views/SynopsisIndex.vue";
import CategoryIndex from "&synopsis/views/CategoryIndex.vue";
import SynopsisShow from "&synopsis/views/SynopsisShow.vue";
import SynopsisEpisodes from "&synopsis/views/SynopsisEpisodes.vue";
import SynopsisNotes from "&synopsis/views/SynopsisNotes.vue";
import SynopsisArchive from "&synopsis/views/SynopsisArchive.vue";
import SynopsisTodoList from "&synopsis/views/SynopsisTodoList.vue";
import PlaceIndex from "&synopsis/views/PlaceIndex.vue";
import CharacterIndex from "&synopsis/views/CharacterIndex.vue";

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
            {
                path: ':id-:slug/archives',
                component: SynopsisArchive,
                name: "SynopsisArchive",
            },
            {
                path: ':id-:slug/notes',
                component: SynopsisNotes,
                name: "SynopsisNotes",
            },
            {
                path: ':id-:slug/todo',
                component: SynopsisTodoList,
                name: "SynopsisTodoList",
            },
        ]
    },
    {
        path: "/category",
        name: "CategoryIndex",
        component: CategoryIndex
    },
    {
        path: "/place",
        name: "PlaceIndex",
        component: PlaceIndex
    },
    {
        path: "/character",
        name: "CharacterIndex",
        component: CharacterIndex
    }
];

export default createRouter({
    routes: routes,
    linkActiveClass: 'active',
    linkExactActiveClass: 'active',
    history: createWebHashHistory()
});