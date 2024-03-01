<template>
    <article>
        <h2 class="h5">{{ label }}</h2>
        <QuillEditor theme="snow" toolbar="#toolbar" contentType="html" :placeholder="placeholder" v-model:content="content">
            <template #toolbar>
                <div id="toolbar">
                    <span class="ql-formats">
                        <select class="ql-size">
                            <option value="small">Petit</option>
                            <option selected="selected">Normal</option>
                            <option value="large">Large</option>
                            <option value="huge">Grand</option>
                        </select>
                    </span>
                    <span class="ql-formats">
                        <button type="button" class="ql-bold">
                            <i class="fa-solid fa-bold"></i>    
                        </button>
                        <button type="button" class="ql-italic">
                            <i class="fa-solid fa-italic"></i>
                        </button>
                        <button type="button" class="ql-underline">
                            <i class="fa-solid fa-underline"></i>
                        </button>
                        <button type="button" class="ql-strike">
                            <i class="fa-solid fa-strikethrough"></i>
                        </button>
                    </span>
                    <span class="ql-formats">
                        <button type="button" class="ql-align">
                            <i class="fa-solid fa-align-left"></i>
                        </button>
                        <button type="button" class="ql-align" value="center">
                            <i class="fa-solid fa-align-center"></i>
                        </button>
                        <button type="button" class="ql-align" value="right">
                            <i class="fa-solid fa-align-right"></i>
                        </button>
                        <button type="button" class="ql-align" value="justify">
                            <i class="fa-solid fa-align-justify"></i>
                        </button>
                    </span>
                    <span class="ql-formats">
                        <button type="button" class="ql-list" value="ordered">
                            <i class="fa-solid fa-list-ol"></i>
                        </button>
                        <button type="button" class="ql-list" value="bullet">
                            <i class="fa-solid fa-list-ul"></i>
                        </button>
                        <button type="button" class="ql-indent" value="-1">
                            <i class="fa-solid fa-outdent"></i>
                        </button>
                        <button type="button" class="ql-indent" value="+1">
                            <i class="fa-solid fa-indent"></i>
                        </button>
                    </span>
                    <span class="ql-formats">
                        <button type="button" class="ql-script" value="sub">
                            <i class="fa-solid fa-subscript"></i>
                        </button>
                        <button type="button" class="ql-script" value="super">
                            <i class="fa-solid fa-superscript"></i>
                        </button>
                        <button type="button" class="ql-blockquote">
                            <i class="fa-solid fa-quote-left"></i>
                        </button>
                    </span>
                    <span class="ql-formats">
                        <button type="button" class="ql-clean">
                            <i class="fa-solid fa-text-slash"></i>
                        </button>
                    </span>
                    <span class="text-success" role="button" @click="onSave" v-tooltip="'Sauvegarder'">
                        <i class="fa-solid fa-floppy-disk"></i>
                    </span>
                </div>
            </template>
        </QuillEditor>
    </article>
</template>

<script>
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';

export default {
    name: 'Description',

    props: {
        data: {
            type: String,
            default: null
        },
        label: {
            type: String,
            default: 'Description'
        },
        placeholder: {
            type: String,
            default: 'Renseignez la description'
        }
    },

    components: {
        QuillEditor,
    },

    data() {
        return {
            content: null
        }
    },

    mounted () {
        this.content = this.data;
    },

    methods: {
        onSave() {
            if (this.data === this.content) {
                return;
            }

            this.$emit('on-save', this.content);
        }
    },
}
</script>

<style>
.ql-container {
    font-size: 16px;
    font-family: inherit;
}
</style>
