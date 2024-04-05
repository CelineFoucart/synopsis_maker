<template>
    <section class="card shadow-sm">
        <div class="card-header bg-light border-bottom-0 pb-0">
            <h3 class="h5 fw-bold card-title mb-0">{{ title }}</h3>
        </div>
        <div class="card-body bg-light">
            <div class="d-flex flex-column gap-2">
                <div class="card card-task shadow-sm bg-white" v-for="task in tasks" :key="task.id">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-9">
                                <h4 class="h6 mb-0">
                                    {{ task.title }}
                                </h4>
                                <div v-if="task.content !== null && task.content.length > 0">
                                    <i class="fa-solid fa-align-left" v-tooltip="'Cette tâche comporte une description'"></i>
                                </div>
                            </div>
                            <div class="col-3 text-end">
                                <i class="fa-solid fa-pen fa-fw button" v-tooltip="'Editer'" @click="onEdit(task)"></i>
                                <i class="fa-solid fa-trash fa-fw button text-danger" v-tooltip="'Supprimer'" @click="onDelete(task)"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer bg-light border-top-0 pt-0">
            <form action="" method="post" @submit.prevent="onAppend">
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" v-model="newTask" placeholder="tâche..." :class="{ 'is-invalid': v$.newTask.$errors.length }" :aria-describedby="'button-addon'+id">
                    <button class="btn btn-dark" type="submit" :id="'button-addon'+id">
                        <i class="fa-solid fa-plus fa-fw" v-if="!loading"></i>
                        <i class="fa-solid fa-spinner fa-spin" v-if="loading"></i>
                    </button>
                </div>
                <div class="invalid-feedback">
                    Ce champ est obligatoire et doit faire entre 2 et 255 caractères.
                </div>
            </form>
        </div>
        <TaskModal :task="editedTask" :category="title" @on-close="editModal = false" v-if="editModal"></TaskModal>
        <Delete :title="editedTask.title" :loading="loading" v-if="deleteModal" @on-confirm="deleteTask" @on-cancel="deleteModal = false"></Delete>
    </section>
</template>

<script>
import { mapStores } from "pinia";
import { useSynopsisStore } from '&synopsis/stores/synopsis.js';
import { useVuelidate } from '@vuelidate/core'
import { required, maxLength, minLength  } from '@vuelidate/validators';
import TaskModal from '&synopsis/components/synopsis_todo/TaskModal.vue';
import { createToastify } from '&utils/toastify.js';
import Delete from '&utils/Delete.vue';

export default {
    name: 'TodoColumn',

    components: {
        TaskModal,
        Delete
    },

    props: {
        title: String,
        synopsis: Object,
        id: Number
    },

    data() {
        return {
            newTask: null,
            v$: useVuelidate(),
            loading: false,
            editModal: false,
            deleteModal: false,
            editedTask: null
        }
    },

    validations () {
        return {
            newTask: { required, maxLength: maxLength(255), minLength: minLength(2) },
        }
    },

    computed: {
        ...mapStores(useSynopsisStore),

        tasks() {
            const tasks = [];

            this.synopsis.tasks.forEach(task => {
                if (task.category == this.id) {
                    tasks.push(task);
                }
            });

            return tasks;
        }
    },

    methods: {
        async onAppend() {
            this.loading = true;
            const result = await this.v$.$validate();
            if (!result) {
                createToastify('Le formulaire comporte des erreurs.', 'error');
                this.loading = false;
                return;
            }

            const newTask = {
                position: this.tasks.length,
                title: this.newTask,
                content: '',
                category: this.id
            };

            const status = await this.synopsisStore.addTask(newTask);
            if (!status) {
                createToastify("L'enregistrement a échoué.", 'error');
            } else {
                this.newTask = null;
                this.v$.newTask.$reset()
            }

            this.loading = false;
        },

        async deleteTask() {
            this.loading = true;
            const status = await this.synopsisStore.removeTask(this.editedTask);
            if (!status) {
                createToastify("La suppression a échoué.", 'error');
            }

            this.loading = false;
            this.deleteModal = false;
            this.editedTask = null;
        },

        onEdit(task) {
            this.editedTask = task;
            this.editModal = true;
        },

        onDelete(task) {
            this.editedTask = task;
            this.deleteModal = true;
        }
    },
}
</script>

<style scoped>
.card-task:hover {
    border-color: rgb(13, 110, 253);
}
</style>