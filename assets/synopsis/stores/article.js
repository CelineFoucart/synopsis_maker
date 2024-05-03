import { defineStore } from 'pinia';
import axios from 'axios';

export const useArticleStore = defineStore('article', {
    state: () => ({ articles: [], loading: false }),

    actions: {
        setArticles(articles) {
            this.articles = articles;
        },

        async getAll() { 
            try {
                const url = Routing.generate("api_article_index");
                const response = await axios.get(url);
                this.setArticles(response.data);
                return true;
            } catch (error) {
                this.setArticles([]);
                return false;
            }
        },

        async edit(articleId, data) {
            try {
                const url = Routing.generate("api_article_edit", {id: articleId});
                const response = await axios.put(url, data);

                const index = this.articles.findIndex((article) => article.id === articleId);
                if (index !== -1) {
                    this.articles[index] = response.data;
                }

                return true;
            } catch (error) {
                return false;
            }
        },

        async delete(articleId) {
            try {
                const url = Routing.generate("api_article_delete", {id: articleId});
                await axios.delete(url);
                
                const index = this.articles.findIndex((article) => article.id === articleId);
                if (index !== -1) {
                    this.articles.splice(index, 1);
                }

                return true;
            } catch (error) {
                return false;
            }
        }
    }
})