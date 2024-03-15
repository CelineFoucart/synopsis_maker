import { defineStore } from 'pinia';
import axios from 'axios';

export const useSynopsisStore = defineStore('synopsis', {
    state: () => ({ 
        synopses: [], 
        pagination: {current: 0, limit: 10, totalCount: 0, firstItemNumber: 0, lastItemNumber: 0, last: 0, pagesInRange: []},
        synopsis: null, 
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
                this.pagination.limit = response.data.meta.numItemsPerPage;
                this.synopsis = null;

                return true;
            } catch (error) {
                this.synopses = [];
                this.pagination = {current: 0, limit: 10, totalCount: 0, firstItemNumber: 0, lastItemNumber: 0, last: 0, pagesInRange: []};
                this.synopsis = null;

                return false;
            }
        },

        async postSynopsis(data) {
            try {
                const url = Routing.generate("api_synopsis_create");
                const response = await axios.post(url, data);
                this.synopsis = response.data;

                return true;
            } catch (error) {
                return false;
            }
        },

        async getSynopsis(params) {
            try {
                const url = Routing.generate("api_synopsis_show", {id: params.id});
                const response = await axios.get(url);
                this.synopsis = response.data;
                return true;
            } catch (error) {
                return false;
            }
        },

        async putSynopsis(data, id) {
            try {
                const url = Routing.generate("api_synopsis_edit", {id: id});
                const response = await axios.put(url, data);
                this.synopsis = response.data;

                return true;
            } catch (error) {
                return false;
            }
        },

        async putSynopsisLegend(id, data) {
            try {
                const url = Routing.generate("api_synopsis_legend_edit", {id: id});
                await axios.put(url, data);
                if (this.synopsis) {
                    this.synopsis.legend = data.legend;
                }
                return true;
            } catch (error) {
                return false;
            }
        },

        async deleteSynopsis(id) {
            try {
                const url = Routing.generate("api_synopsis_delete", {id: id});
                await axios.delete(url);
                return true;
            } catch (error) {
                return false;
            }
        },

        async postChapter(data) {
            try {
                const url = Routing.generate("api_synopsis_chapter_create", {id: this.synopsis.id});
                const response = await axios.post(url, data);
                this.synopsis.chapters = response.data.chapters;
                return true;
            } catch (error) {
                return false;
            }
        },

        async putChapter(data, id) {
            try {
                const url = Routing.generate("api_synopsis_chapter_edit", {id: this.synopsis.id, chapterId: id});
                const response = await axios.put(url, data);
                this.synopsis.chapters = response.data.chapters;

                return true;
            } catch (error) {
                return false;
            }
        },

        async deleteChapter(id) {
            try {
                const url = Routing.generate("api_synopsis_chapter_delete", {id: this.synopsis.id, chapterId: id});
                await axios.delete(url);

                const index = this.synopsis.chapters.findIndex(element => element.id === id);
                if (index !== -1) {
                    this.synopsis.chapters.splice(index, 1);
                }

                return true;
            } catch (error) {
                return false;
            }
        },

        async postEpisode(data, chapterId) {
            try {
                const url = Routing.generate("api_synopsis_episode_create", {id: this.synopsis.id, chapter: chapterId});
                const response = await axios.post(url, data);
                this.synopsis.chapters = response.data.chapters;
                this.synopsis.episodes = response.data.episodes;

                return true;
            } catch (error) {
                return false;
            }
        },

        async putEpisode(data, id) {
            try {
                const url = Routing.generate("api_synopsis_episode_edit", {id: this.synopsis.id, episodeId: id});
                const response = await axios.put(url, data);
                this.synopsis.chapters = response.data.chapters;
                this.synopsis.episodes = response.data.episodes;

                return true;
            } catch (error) {
                return false;
            }
        },

        async deleteEpisode(id, chapterId) {
            try {
                const url = Routing.generate("api_synopsis_episode_delete", {id: this.synopsis.id, episodeId: id});
                await axios.delete(url);

                if (chapterId === null) {
                    const index = this.synopsis.episodes.findIndex(element => element.id === id);
                    if (index !== -1) {
                        this.synopsis.episodes.splice(index, 1);
                    }
                } else {
                    const indexChapter = this.synopsis.chapters.findIndex(element => element.id === chapterId);
                    if (indexChapter !== -1) {
                        const index = this.synopsis.chapters[indexChapter].episodes.findIndex(element => element.id === id);
                        if (index !== -1) {
                            this.synopsis.chapters[indexChapter].episodes.splice(index, 1);
                        }
                    }
                }

                return true;
            } catch (error) {
                return false;
            }
        }
    }
})