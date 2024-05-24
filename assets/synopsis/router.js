import { createRouter, createWebHashHistory } from "vue-router";
import SynopsisIndex from "&synopsis/views/synopsis/SynopsisIndex.vue";
import SynopsisShow from "&synopsis/views/synopsis/SynopsisShow.vue";
import SynopsisEpisodes from "&synopsis/views/synopsis/SynopsisEpisodes.vue";
import SynopsisNotes from "&synopsis/views/synopsis/SynopsisNotes.vue";
import SynopsisArchive from "&synopsis/views/synopsis/SynopsisArchive.vue";
import SynopsisTodoList from "&synopsis/views/synopsis/SynopsisTodoList.vue";
import WorldBuilding from "&synopsis/views/synopsis/WorldBuilding.vue";
import SynopsisSettings from "&synopsis/views/synopsis/SynopsisSettings.vue";
import Settings from "&synopsis/views/Settings.vue";
import PlaceIndex from "&synopsis/views/PlaceIndex.vue";
import CharacterIndex from "&synopsis/views/CharacterIndex.vue";
import ArticleIndex from "&synopsis/views/ArticleIndex.vue";

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
            {
                path: ':id-:slug/worldbuilding',
                component: WorldBuilding,
                name: "WorldBuilding",
            },
            {
                path: ':id-:slug/settings',
                component: SynopsisSettings,
                name: "SynopsisSettings",
            },
        ]
    },
    {
        path: "/settings",
        name: "Settings",
        component: Settings
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
    },
    {
        path: "/article",
        name: "ArticleIndex",
        component: ArticleIndex
    }
];

export default createRouter({
    routes: routes,
    linkActiveClass: 'active',
    linkExactActiveClass: 'active',
    history: createWebHashHistory()
});