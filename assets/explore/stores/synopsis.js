import { defineStore } from 'pinia';
import axios from 'axios';

export const useSynopsisStore = defineStore('synopsis', {
    state: () => ({ 
        synopses: [], 
        pagination: {current: 0, limit: 10, totalCount: 0, firstItemNumber: 0, lastItemNumber: 0, last: 0, pagesInRange: []},
        synopsis: null, 
        loading: false,
    }),

    actions: {
        async getSynopses(page = 1, limit = 10, sortField = 's.title', sortOrder = 'asc', query = null) { 
            this.loading = true;
            console.log(this.loading);
            try {
                const params = { page: page, limit: limit, field: sortField, order: sortOrder, query: query };

                const url = Routing.generate("api_explore_index", params);
                const response = await axios.get(url);
                this.synopses = response.data.synopses;
                this.pagination = response.data.meta;
                this.pagination.limit = response.data.meta.numItemsPerPage;
                this.synopsis = null;
                this.loading = false;

                return true;
            } catch (error) {
                this.synopses = [];
                this.pagination = {current: 0, limit: 10, totalCount: 0, firstItemNumber: 0, lastItemNumber: 0, last: 0, pagesInRange: []};
                this.synopsis = null;
                this.loading = false;

                return false;
            }
        },

        async getSynopsis(params) {
            this.loading = true;
            try {
                const url = Routing.generate("api_explore_synopsis", {id: params.id});
                const response = await axios.get(url);
                this.synopsis = response.data;
                this.loading = false;
                return true;
            } catch (error) {
                this.loading = false;
                return false;
            }
        }
    }
})