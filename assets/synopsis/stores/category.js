import { defineStore } from 'pinia';
import axios from 'axios';

export const useCategoryStore = defineStore('category', {
    state: () => ({ categories: [] }),

    actions: {
        async getCategories() { 
            try {
                const url = Routing.generate("api_category_index");
                const response = await axios.get(url);
                this.categories = response.data;
                return true;
            } catch (error) {
                this.categories = [];
                return false;
            }
        },

        async postCategory(data) {
            try {
                const url = Routing.generate("api_category_create");
                const response = await axios.post(url, data);
                this.categories.push(response.data);

                return true;
            } catch (error) {
                return false;
            }
        },

        async putCategory(categoryId, data) {
            try {
                const url = Routing.generate("api_category_edit", {id: categoryId});
                const response = await axios.put(url, data);

                const index = this.categories.findIndex((category) => category.id === categoryId);
                if (index !== -1) {
                    this.categories[index] = response.data;
                }

                return true;
            } catch (error) {
                return false;
            }
        },

        async deleteCategory(categoryId) {
            try {
                const url = Routing.generate("api_category_delete", {id: categoryId});
                await axios.delete(url);
                
                const index = this.categories.findIndex((category) => category.id === categoryId);
                if (index !== -1) {
                    this.categories.splice(index, 1);
                }

                return true;
            } catch (error) {
                return false;
            }
        }
    }
})