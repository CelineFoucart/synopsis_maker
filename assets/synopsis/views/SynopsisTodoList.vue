<template>
    <div>
        <Error v-if="error"></Error>
        <article v-if="!loading && synopsisStore.synopsis !== null">
            <HeaderSynopsis :synopsis="synopsisStore.synopsis"></HeaderSynopsis>
            <div class="border-top border-bottom my-3 p-2">
                <h2 class="h5 mb-0 text-secondary">Sur cette page, gérez la liste des tâches à faire pour ce synopsis.</h2>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <TodoColumn :title="'À faire'" :id="0" :synopsis="synopsisStore.synopsis"></TodoColumn>
                </div>
                <div class="col-md-4">
                    <TodoColumn :title="'En cours'" :id="1" :synopsis="synopsisStore.synopsis"></TodoColumn>
                </div>
                <div class="col-md-4">
                    <TodoColumn :title="'Terminé'" :id="2" :synopsis="synopsisStore.synopsis"></TodoColumn>
                </div>
            </div>
        </article>
        <Loading v-if="loading"></Loading>
    </div>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { createToastify } from '&utils/toastify.js';
import Loading from '&utils/Loading.vue';
import Error from '&utils/Error.vue';
import HeaderSynopsis from '&synopsis/components/synopsis_show/HeaderSynopsis.vue';
import TodoColumn from '&synopsis/components/synopsis_todo/TodoColumn.vue';

export default {
    name: 'SynopsisTodoList',

    components: {
        Loading,
        HeaderSynopsis,
        TodoColumn,
        Error
    },

    data() {
        return {
            error: false,
            loading: false,
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),
    },

    async mounted () {
        if (this.synopsisStore.synopsis !== null) {
            return;
        }

        this.loading = true;
        const status = await this.synopsisStore.getSynopsis(this.$route.params);
        if (!status) {
            createToastify("Ce synopsis n'existe pas.", 'error');
            this.error = true;
        } else {
            this.legend = this.synopsisStore.synopsis.legend;
        }
        
        this.loading = false;
    },
}
</script>

<style scoped>

</style>