<template>
    <Error v-if="error"></Error>
    <article v-if="synopsisStore.synopsis !== null">
        <HeaderSynopsis :synopsis="synopsisStore.synopsis"></HeaderSynopsis>

        <div class="row">
            <div class="col-md-8 col-lg-9">

            </div>
            <div class="col-md-4 col-lg-3">
                <Summary></Summary>
            </div>
        </div>
    </article>
</template>

<script lang="js">
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { createToastify } from '&utils/toastify.js';
import Error from '&utils/Error.vue';
import HeaderSynopsis from '&synopsis/components/synopsis_show/HeaderSynopsis.vue';
import { useArticleCategoryStore } from '&synopsis/stores/articleCategory.js';
import Summary from '&synopsis/components/synopsis_article/Summary.vue';

export default {
    name: 'WorldBuilding',

    components: {
        HeaderSynopsis,
        Error,
        Summary
    },

    data() {
        return {
            error: false,
        }
    },

    computed: {
        ...mapStores(useSynopsisStore, useArticleCategoryStore),
    },

    async mounted () {
        if (this.synopsisStore.synopsis !== null) {
            return;
        }
        
        const status = await this.synopsisStore.getSynopsis(this.$route.params);
        if (!status) {
            createToastify("Ce synopsis n'existe pas.", 'error');
            this.error = true;
        }

        const statusCategory = await this.articleCategoryStore.getCategories();
        if (!statusCategory) {
            createToastify('Le chargement des catégories a échoué', 'error');
        }
    },
}
</script>
