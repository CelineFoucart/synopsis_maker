import { defineStore } from 'pinia';
import axios from 'axios';

export const usePlaceStore = defineStore('place', {
    state: () => ({ places: [] }),

    actions: {
        setPlaces(places) {
            this.places = places;
        },

        async getAll() { 
            try {
                const url = Routing.generate("api_place_index");
                const response = await axios.get(url);
                this.setPlaces(response.data);
                return true;
            } catch (error) {
                this.setPlaces([]);
                return false;
            }
        },

        async edit(placeId, data) {
            try {
                const url = Routing.generate("api_place_edit", {id: placeId});
                const response = await axios.put(url, data);

                const index = this.places.findIndex((place) => place.id === placeId);
                if (index !== -1) {
                    this.places[index] = response.data;
                }

                return true;
            } catch (error) {
                return false;
            }
        },

        async delete(placeId) {
            try {
                const url = Routing.generate("api_place_delete", {id: placeId});
                await axios.delete(url);
                
                const index = this.places.findIndex((place) => place.id === placeId);
                if (index !== -1) {
                    this.places.splice(index, 1);
                }

                return true;
            } catch (error) {
                return false;
            }
        }
    }
})