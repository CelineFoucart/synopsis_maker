<template>
    <div>
        <Error v-if="error"></Error>
        <article v-if="synopsisStore.synopsis !== null">
            <HeaderSynopsis :synopsis="synopsisStore.synopsis"></HeaderSynopsis>
            <div class="border-top border-bottom my-3 p-2">
                <div class="row">
                    <div class="col-md-6">
                        <h2 class="h5 mb-0 text-secondary">Sur cette page, gérez la liste des tâches à faire pour ce synopsis.</h2>
                    </div>
                    <div class="col-md-6">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" :style="{width: progressPercent}" aria-valuemin="0" aria-valuemax="100">
                                {{ progressPercent.replace('.', ',') }}
                            </div>
                        </div>
                        <div class="small text-center">
                            Progression : {{progressPercent.replace('.', ',')}} ({{doneTasks}}/{{ synopsisStore.synopsis.tasks.length }})
                        </div>
                    </div>
                </div>
            </div>
            <div class="row g-2">
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
        <Loading v-if="synopsisStore.loading"></Loading>
    </div>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { createToastify } from '&utils/toastify.js';
import Error from '&utils/Error.vue';
import HeaderSynopsis from '&synopsis/components/synopsis_show/HeaderSynopsis.vue';
import TodoColumn from '&synopsis/components/synopsis_todo/TodoColumn.vue';
import Sortable from 'sortablejs';
import Loading from '&utils/Loading.vue';

export default {
    name: 'SynopsisTodoList',

    components: {
        HeaderSynopsis,
        TodoColumn,
        Error,
        Loading
    },

    data() {
        return {
            error: false,
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),

        doneTasks() {
            let doneTasks = 0;

            if (this.synopsisStore.synopsis === null) {
                return doneTasks;
            }

            this.synopsisStore.synopsis.tasks.forEach(task => {
                if (task.category === 2) {
                    doneTasks++; 
                }
            });

            return doneTasks;
        },

        progressPercent() {
            if (this.synopsisStore.synopsis === null) {
                return '0%';
            }

            let percent = (this.doneTasks / this.synopsisStore.synopsis.tasks.length) * 100;
            percent = Math.round(percent * 100) / 100

            if (isNaN(percent)) {
                percent = 0;
            }

            return `${percent}%`;
        }
    },

    async mounted () {
        const status = await this.synopsisStore.getSynopsis(this.$route.params);
        if (!status) {
            createToastify("Ce synopsis n'existe pas.", 'error');
            this.error = true;
        }

        this.enableSortable();
        
    },

    updated() {
        this.enableSortable();
    },

    methods: {
        enableSortable() {
            document.querySelectorAll('.sortable-list').forEach(element => {
                new Sortable(element, {
                    group: 'shared',
                    ghostClass: 'blue-background-class',
                    animation: 150,
                    onEnd: async (evt) => {
                        const category = evt.to.dataset.list;
                        const taskId = evt.item.dataset.id;
                        const position = evt.newIndex;

                        const status = await this.synopsisStore.reorderTask(taskId, category, position);
                        if (!status) {
                            createToastify("L'opération a échoué.", "error")
                        }
                    },
                });
            });
        }
    },
}
</script>
