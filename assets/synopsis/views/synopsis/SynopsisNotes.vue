<template>
    <Error v-if="error"></Error>
    <article v-if="synopsisStore.synopsis !== null">
        <HeaderSynopsis :synopsis="synopsisStore.synopsis"></HeaderSynopsis>
        
        <section class="mt-4">
            <h2 class="h5 d-flex justify-content-between">
                <span>Notes, remarques et réflexions sur ce synopsis</span>
                <span class="text-success" role="button" @click="onSave" v-tooltip="'Sauvegarder'">
                    <i class="fa-solid fa-floppy-disk"></i>
                </span>
            </h2>
            <Description v-model:data="notes"></Description>
        </section>
    </article>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { createToastify } from '&utils/toastify.js';
import Error from '&utils/Error.vue';
import HeaderSynopsis from '&synopsis/components/synopsis_show/HeaderSynopsis.vue';
import Description from '&utils/Description.vue';

export default {
    name: 'SynopsisNotes',

    components: {
        HeaderSynopsis,
        Description,
        Error,
    },

    data() {
        return {
            error: false,
            notes: null
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),
    },

    async mounted () {
        if (this.synopsisStore.synopsis !== null) {
            this.notes = this.synopsisStore.synopsis.notes;
            return;
        }
        
        this.status = await this.synopsisStore.getSynopsis(this.$route.params);
        if (!this.status) {
            createToastify("Ce synopsis n'existe pas.", 'error');
            this.error = true;
        }

        this.notes = this.synopsisStore.synopsis.notes;
    },

    methods: {
        async onSave() {
            const status = await this.synopsisStore.putSynopsisNotes(this.notes, this.synopsisStore.synopsis.id);
            if (!status) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
            }
        },
    },
}
</script>
