import { defineStore } from 'pinia';
import axios from 'axios';

export const useArticleCategoryStore = defineStore('articleCategory', {
    state: () => ({ categories: [], loading: false }),

    actions: {
        async getCategories() { 
            try {
                this.loading = true;
                const url = Routing.generate("api_article_category_index");
                const response = await axios.get(url);
                this.categories = response.data;
                this.loading = false;
                return true;
            } catch (error) {
                this.categories = [];
                this.loading = false;
                return false;
            }
        },

        async postCategory(data) {
            try {
                this.loading = true;
                const url = Routing.generate("api_article_category_create");
                const response = await axios.post(url, data);
                this.categories.push(response.data);
                this.loading = false;

                return true;
            } catch (error) {
                this.loading = false;
                return false;
            }
        },

        async putCategory(categoryId, data) {
            try {
                this.loading = true;
                const url = Routing.generate("api_article_category_edit", {id: categoryId});
                const response = await axios.put(url, data);

                const index = this.categories.findIndex((category) => category.id === categoryId);
                if (index !== -1) {
                    this.categories[index] = response.data;
                }
                this.loading = false;

                return true;
            } catch (error) {
                this.loading = false;
                return false;
            }
        },

        async deleteCategory(categoryId) {
            try {
                this.loading = true;
                const url = Routing.generate("api_article_category_delete", {id: categoryId});
                await axios.delete(url);
                
                const index = this.categories.findIndex((category) => category.id === categoryId);
                if (index !== -1) {
                    this.categories.splice(index, 1);
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