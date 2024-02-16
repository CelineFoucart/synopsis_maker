import { defineStore } from 'pinia';
import axios from 'axios';

export const useSynopsisStore = defineStore('synopsis', {
    state: () => ({ synopses: [], pagination: {current: 1, limit: 10, totalCount: 0} }),

    actions: {
        async getSynopsis(page = 1, limit = 10, query = null) { 
            try {
                const params = { page: page, limit: limit, query: query };
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