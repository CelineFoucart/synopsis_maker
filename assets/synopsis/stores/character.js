import { defineStore } from 'pinia';
import axios from 'axios';

export const useCharacterStore = defineStore('character', {
    state: () => ({ characters: [] }),

    actions: {
        setCharacters(characters) {
            this.characters = characters;
        },

        async getAll() { 
            try {
                const url = Routing.generate("api_character_index");
                const response = await axios.get(url);
                this.setCharacters(response.data);
                return true;
            } catch (error) {
                this.setCharacters([]);
                return false;
            }
        },

        async edit(characterId, data) {
            try {
                const url = Routing.generate("api_character_edit", {id: characterId});
                const response = await axios.put(url, data);

                const index = this.characters.findIndex((character) => character.id === characterId);
                if (index !== -1) {
                    this.characters[index] = response.data;
                }

                return true;
            } catch (error) {
                return false;
            }
        },

        async delete(characterId) {
            try {
                const url = Routing.generate("api_character_delete", {id: characterId});
                await axios.delete(url);
                
                const index = this.characters.findIndex((character) => character.id === characterId);
                if (index !== -1) {
                    this.characters.splice(index, 1);
                }

                return true;
            } catch (error) {
                return false;
            }
        }
    }
})