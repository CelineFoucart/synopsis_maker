<template>
    <section class="card shadow-sm">
        <div class="card-header">
            <h3 class="h6 fw-bold card-title mb-0">{{ title }}</h3>
        </div>
        <div class="card-body"></div>
        <div class="card-footer">
            <form action="" method="post" @submit.prevent="onAppend">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" v-model="newTask" placeholder="tâche..." :aria-describedby="'button-addon'+id">
                    <button class="btn btn-dark" type="submit" :id="'button-addon'+id">
                        <i class="fa-solid fa-plus fa-fw"></i>
                    </button>
                </div>
            </form>
        </div>
    </section>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';

export default {
    name: 'TodoColumn',

    props: {
        title: String,
        synopsis: Object,
        id: Number
    },

    data() {
        return {
            newTask: null
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),

        tasks() {
            const tasks = [];

            this.synopsis.tasks.forEach(task => {
                if (task.id === this.id) {
                    tasks.push(task);
                }
            });
        }
    },

    methods: {
        async onAppend() {
            const newTask = {
                position: this.tasks.length,
                title: this.newTask,
                content: '',
                category: this.id
            };

            const status = await this.synopsisStore.addTask(newTask);
            if (!status) {
                createToastify("L'enregistrement a échoué.", 'error');
            }
        },

        onEdit() {

        },

        onDelete(task) {

        }
    },
}
</script>

<style scoped>

</style>