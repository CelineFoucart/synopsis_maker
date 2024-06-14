import { defineStore } from 'pinia';
import axios from 'axios';

export const useUserStore = defineStore('user', {
    state: () => ({ 
        users: [],
        user: null,
        loading: false
    }),

    actions: {
        async getUsers() { 
            try {
                this.users = [];
                const response = await axios.get(Routing.generate("api_explore_user_index"));
                this.users = response.data;
                return true;
            } catch (error) {
                return false;
            }
        },

        async getUser(id) {
            try {
                this.loading = true;
                this.user = null;
                const response = await axios.get(Routing.generate("api_explore_user_show", {id: id}));
                this.user = response.data;
                this.loading = false;
                return true;
            } catch (error) {
                this.loading = false;
                return false;
            }
        }
    }
})