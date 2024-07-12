<template>
    <Error v-if="error"></Error>
    <article v-if="synopsisStore.synopsis !== null">
        <HeaderSynopsis :synopsis="synopsisStore.synopsis" @on-delete="deleteSynopsis"></HeaderSynopsis>
        <p class="lead mt-3">{{ synopsisStore.synopsis.pitch }}</p>
        <section>
            <h2 class="h5">
                Description
                <span class="text-success" role="button" @click="editMode = true" v-tooltip="'Sauvegarder'" v-if="editMode === false">
                    <i class="fas fa-pen fa-fw"></i>
                </span>
            </h2>
            <Description v-model:data="description" :editMode="editMode"></Description>
            <button class="btn btn-success btn-sm mt-2 me-1" v-if="editMode" @click="onSave">
                <i class="fas fa-save fa-fw"></i> Sauvegarder
            </button>
            <button class="btn btn-secondary btn-sm mt-2" v-if="editMode" @click="editMode = false">
                <i class="fas fa-times fa-fw"></i> Fermer
            </button>
        </section>
        <div class="row">
            <div class="col-lg-6">
                <SynopsisPlace></SynopsisPlace>
            </div>
            <div class="col-lg-6">
                <SynopsisCharacter></SynopsisCharacter>
            </div>
        </div>
        <MetaData :element="synopsisStore.synopsis"></MetaData>
    </article>
    <Loading v-if="synopsisStore.loading"></Loading>
</template>

<script lang="js">
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { createToastify } from '&utils/toastify.js';
import Error from '&utils/Error.vue';
import HeaderSynopsis from '&synopsis/components/synopsis_show/HeaderSynopsis.vue';
import Description from '&utils/Description.vue';
import MetaData from '&synopsis/components/synopsis_show/MetaData.vue';
import SynopsisPlace from '&synopsis/components/synopsis_show/SynopsisPlace.vue';
import SynopsisCharacter from '&synopsis/components/synopsis_show/SynopsisCharacter.vue';
import Loading from '&utils/Loading.vue';

export default {
    name: 'SynopsisShow',

    components: {
        HeaderSynopsis,
        Description,
        Error,
        MetaData,
        SynopsisPlace,
        SynopsisCharacter,
        Loading
    },

    data() {
        return {
            error: false,
            description: '',
            editMode: false,
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),
    },
    
    async mounted () {
        this.status = await this.synopsisStore.getSynopsis(this.$route.params);
        if (!this.status) {
            createToastify("Ce synopsis n'existe pas.", 'error');
            this.error = true;
        } else {
            this.description = this.synopsisStore.synopsis.description ? this.synopsisStore.synopsis.description : '';
        }
    },

    methods: {
        async onSave() {
            const data = {
                title: this.synopsisStore.synopsis.title, 
                pitch: this.synopsisStore.synopsis.pitch, 
                categories: this.synopsisStore.synopsis.categories,
                description: this.description
            };

            const status = await this.synopsisStore.putSynopsis(data, this.synopsisStore.synopsis.id);
            if (!status) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
            }

            this.editMode = false;
        },

        async deleteSynopsis() {
            const status = await this.synopsisStore.deleteSynopsis(this.synopsisStore.synopsis.id);
            if (status) {
                createToastify('Le synopsis a été supprimé.', 'success');
                this.$router.push('/synopsis');
            } else {
                createToastify('La suppression a échoué.', 'error');
            }
        }
    },
}
</script>

<style scoped>
p {
    white-space: pre-wrap
}
</style>