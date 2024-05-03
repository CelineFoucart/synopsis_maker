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
                this.loading = true;
                const url = Routing.generate("api_article_index");
                const response = await axios.get(url);
                this.setArticles(response.data);
                this.loading = false;
                return true;
            } catch (error) {
                this.setArticles([]);
                this.loading = false;
                return false;
            }
        },

        async edit(articleId, data) {
            try {
                this.loading = true;
                const url = Routing.generate("api_article_edit", {id: articleId});
                const response = await axios.put(url, data);

                const index = this.articles.findIndex((article) => article.id === articleId);
                if (index !== -1) {
                    this.articles[index] = response.data;
                }
                this.loading = false;

                return true;
            } catch (error) {
                this.loading = false;

                return false;
            }
        },

        async delete(articleId) {
            try {
                this.loading = true;
                const url = Routing.generate("api_article_delete", {id: articleId});
                await axios.delete(url);
                
                const index = this.articles.findIndex((article) => article.id === articleId);
                if (index !== -1) {
                    this.articles.splice(index, 1);
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