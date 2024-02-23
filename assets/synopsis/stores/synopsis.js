import { defineStore } from 'pinia';
import axios from 'axios';

export const useSynopsisStore = defineStore('synopsis', {
    state: () => ({ 
        synopses: [], 
        pagination: {current: 0, limit: 10, totalCount: 0, firstItemNumber: 0, lastItemNumber: 0, last: 0, pagesInRange: []} 
    }),

    actions: {
        async getSynopses(page = 1, limit = 10, filters) { 
            try {
                const params = { page: page, limit: limit };

                if (filters.query !== null) {
                    params.query = filters.query;
                }

                params.categories = (filters.selectedCategories.length > 0) ? filters.selectedCategories.join('-') : '-';
                
                const url = Routing.generate("api_synopsis_index", params);
                const response = await axios.get(url);
                this.synopses = response.data.synopses;
                this.pagination = response.data.meta;

                return true;
            } catch (error) {
                this.synopses = [];
                this.pagination = {current: 1, limit: 10, totalCount: 0};

                return false;
            }
        }
    }
})