import { createRouter, createWebHashHistory } from "vue-router";
import ExploreIndex from "&explore/views/ExploreIndex.vue";
import Synopsis from "&explore/views/Synopsis.vue";
import UserShow from "&explore/views/UserShow.vue";

const routes =  [
    { 
        path: '/', 
        name: 'index',
        component: ExploreIndex,
    },
    { 
        path: '/synopsis-:slug', 
        name: 'synopsis_show',
        component: Synopsis,
    },
    { 
        path: '/user-:id', 
        name: 'user_show',
        component: UserShow,
    }
];

export default createRouter({
    routes: routes,
    linkActiveClass: 'active',
    linkExactActiveClass: 'active',
    history: createWebHashHistory()
});