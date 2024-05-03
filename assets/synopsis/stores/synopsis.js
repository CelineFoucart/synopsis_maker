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
        refreshSynopsis(places) {
            this.synopsis.places = places;
        },

        async getSynopses(page = 1, limit = 10, filters) { 
            this.loading = true;
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

        async postSynopsis(data) {
            this.loading = true;
            try {
                const url = Routing.generate("api_synopsis_create");
                const response = await axios.post(url, data);
                this.synopsis = response.data;
                this.loading = false;

                return true;
            } catch (error) {
                this.loading = false;

                return false;
            }
        },

        async getSynopsis(params) {
            this.loading = true;
            try {
                const url = Routing.generate("api_synopsis_show", {id: params.id});
                const response = await axios.get(url);
                this.synopsis = response.data;
                this.loading = false;
                return true;
            } catch (error) {
                this.loading = false;
                return false;
            }
        },

        async putSynopsis(data, id) {
            try {
                this.loading = true;
                const url = Routing.generate("api_synopsis_edit", {id: id});
                const response = await axios.put(url, data);
                this.synopsis = response.data;
                this.loading = false;

                return true;
            } catch (error) {
                this.loading = false;
                return false;
            }
        },

        async putSynopsisLegend(id, data) {
            this.loading = true;
            try {
                const url = Routing.generate("api_synopsis_legend_edit", {id: id});
                await axios.put(url, data);
                if (this.synopsis) {
                    this.synopsis.legend = data.legend;
                }
                this.loading = false;
                return true;
            } catch (error) {
                this.loading = false;
                return false;
            }
        },

        async putSynopsisNotes(data, id) {
            this.loading = true;
            try {
                const url = Routing.generate("api_synopsis_legend_notes", {id: id});
                await axios.put(url, { notes: data });
                if (this.synopsis) {
                    this.synopsis.notes = data;
                }
                this.loading = false;
                return true;
            } catch (error) {
                this.loading = false;
                return false;
            }
        },

        async deleteSynopsis(id) {
            this.loading = true;
            try {
                const url = Routing.generate("api_synopsis_delete", {id: id});
                await axios.delete(url);
                this.loading = false;
                return true;
            } catch (error) {
                this.loading = false;
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
        },

        async validateEpisode(id, chapterId) {
            try {
                const url = Routing.generate("api_synopsis_episode_validate", {id: this.synopsis.id, episodeId: id});
                await axios.put(url);

                if (chapterId === null) {
                    const index = this.synopsis.episodes.findIndex(element => element.id === id);
                    if (index !== -1) {
                        this.synopsis.episodes[index].valid = !this.synopsis.episodes[index].valid;
                    } 
                } else {
                    const indexChapter = this.synopsis.chapters.findIndex(element => element.id === chapterId);
                    if (indexChapter !== -1) {
                        const index = this.synopsis.chapters[indexChapter].episodes.findIndex(element => element.id === id);
                        if (index !== -1) {
                            this.synopsis.chapters[indexChapter].episodes[index].valid = !this.synopsis.chapters[indexChapter].episodes[index].valid;
                        }
                    }
                }

                return true;
            } catch (error) {
                return false;
            }
        },

        async dropEpisodeToChapter(episodeId, newChapter, position = null) {
            this.loading = true;
            try {
                const url = Routing.generate("api_synopsis_episode_position", {id: this.synopsis.id, episodeId: episodeId});
                const response = await axios.put(url, { target: newChapter, position: position });
                this.synopsis.chapters = response.data.chapters;
                this.synopsis.episodes = response.data.episodes;
                this.loading = false;
                return true;
            } catch (error) {
                this.loading = false;
                return false;
            }
        },

        async positionChapterAction(chapterId, position) {
            this.loading = true;
            try {
                const url = Routing.generate("api_synopsis_chapter_position", {id: this.synopsis.id, chapterId: chapterId});
                const response = await axios.put(url, { position: position });
                this.synopsis.chapters = response.data.chapters;
                this.loading = false;
                return true;                
            } catch (error) {
                this.loading = false;
                return false;
                
            }
        },

        async archiveAction(elementId, type) {
            try {
                const routeName = type === 'chapter' ? 'api_synopsis_echapter_archive' : 'api_synopsis_episode_archive';

                const url = Routing.generate(routeName, {id: this.synopsis.id, elementId: elementId});
                const response = await axios.put(url);
                this.synopsis.chapters = response.data.chapters;
                this.synopsis.episodes = response.data.episodes;
                return true;
            } catch (error) {
                return false;
            }
        },

        async addTask(task) {
            try {
                const index = this.synopsis.tasks.findIndex((element) => element.id === task.id);
                if (index !== -1) {
                    this.synopsis.tasks[index] = task;
                } else {
                    this.synopsis.tasks.push(task);
                }

                const url = Routing.generate("api_synopsis_task", {id: this.synopsis.id});
                const response = await axios.put(url, this.synopsis.tasks);
                this.synopsis.tasks = response.data.tasks;
                return true;
            } catch (error) {
                return false;
            }
        },

        async removeTask(task) {
            try {
                const index = this.synopsis.tasks.findIndex((element) => element.id === task.id);
                if (index !== -1) {
                    this.synopsis.tasks.splice(index, 1);
                }

                const url = Routing.generate("api_synopsis_task", {id: this.synopsis.id});
                const response = await axios.put(url, this.synopsis.tasks);
                this.synopsis.tasks = response.data.tasks;

                return true;
            } catch (error) {
                return false;
            }
        },

        async reorderTask(taskId, category, position) {
            try {
                this.loading = true;
                const params = {id: this.synopsis.id, task: taskId, category: category, position: position};
                const url = Routing.generate("api_synopsis_task_reorder", params);
                const response = await axios.put(url, this.synopsis.tasks);
                this.synopsis.tasks = response.data.tasks;
                this.loading = false;
                return true;
            } catch (error) {
                this.loading = false;
                return false;
            }
        },

        async addPlace(data) {
            if (this.synopsis === null) {
                return false;
            }

            try {
                const url = Routing.generate("api_synopsis_place", {id: this.synopsis.id});
                const response = await axios.post(url, data);
                this.synopsis.places = response.data.places;
                return true;
            } catch (error) {
                return false;
            }
        },

        async removePlace(placeId) {
            try {
                const url = Routing.generate("api_synopsis_place_remove", {id: this.synopsis.id, placeId: placeId});
                const response = await axios.delete(url);
                this.synopsis.places = response.data.places;
                return true;
            } catch (error) {
                return false;
            }
        },

        async appendPlaceFromList(placeId) {
            if (this.synopsis === null) {
                return false;
            }

            try {
                const url = Routing.generate("api_synopsis_place_append", {id: this.synopsis.id, placeId: placeId});
                const response = await axios.put(url);
                this.synopsis.places = response.data.places;
                return true;
            } catch (error) {
                return false;
            }
        },

        async addCharacter(data) {
            if (this.synopsis === null) {
                return false;
            }

            try {
                const url = Routing.generate("api_synopsis_character", {id: this.synopsis.id});
                const response = await axios.post(url, data);
                this.synopsis.characters = response.data.characters;
                return true;
            } catch (error) {
                return false;
            }
        },

        async appendCharacterFromList(characterId) {
            if (this.synopsis === null) {
                return false;
            }

            try {
                const url = Routing.generate("api_synopsis_character_append", {id: this.synopsis.id, characterId: characterId});
                const response = await axios.put(url);
                this.synopsis.characters = response.data.characters;
                return true;
            } catch (error) {
                return false;
            }
        },

        async removeCharacter(characterId) {
            try {
                const url = Routing.generate("api_synopsis_character_remove", {id: this.synopsis.id, characterId: characterId});
                const response = await axios.delete(url);
                this.synopsis.characters = response.data.characters;
                return true;
            } catch (error) {
                return false;
            }
        },

        async addArticle(data) {
            if (this.synopsis === null) {
                return false;
            }

            try {
                const url = Routing.generate("api_synopsis_article", {id: this.synopsis.id});
                const response = await axios.post(url, data);
                this.synopsis.articles = response.data.articles;
                return true;
            } catch (error) {
                return false;
            }
        },

        async appendArticleFromList(articleId) {
            if (this.synopsis === null) {
                return false;
            }

            try {
                const url = Routing.generate("api_synopsis_article_append", {id: this.synopsis.id, articleId: articleId});
                const response = await axios.put(url);
                this.synopsis.articles = response.data.articles;
                return true;
            } catch (error) {
                return false;
            }
        },

        async removeArticle(articleId) {
            try {
                const url = Routing.generate("api_synopsis_article_remove", {id: this.synopsis.id, articleId: articleId});
                const response = await axios.delete(url);
                this.synopsis.articles = response.data.articles;
                return true;
            } catch (error) {
                return false;
            }
        },

        async editHomePage(synopsisId, data) {
            try {
                this.loading = true;
                const url = Routing.generate("api_synopsis_homepage_edit", {id: synopsisId});
                await axios.put(url, {homepage: data});
                if (this.synopsis) {
                    this.synopsis.worldbuildingHome = data;
                }
                this.loading = false;

                return true;
            } catch (error) {
                this.loading = false;

                return false;
            }
        }
    }
})