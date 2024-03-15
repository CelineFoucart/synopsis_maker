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
                        <button type="button" class="ql-bold"></button>
                        <button type="button" class="ql-italic"></button>
                        <button type="button" class="ql-underline"></button>
                        <button type="button" class="ql-strike"> </button>
                    </span>
                    <span class="ql-formats">
                        <select class="ql-color"></select>
                        <select class="ql-background"></select>
                    </span>
                    <span class="ql-formats">
                        <select class="ql-align"></select>
                        <button type="button" class="ql-list" value="ordered"></button>
                        <button type="button" class="ql-list" value="bullet"></button>
                        <button type="button" class="ql-indent" value="-1"> </button>
                        <button type="button" class="ql-indent" value="+1"> </button>
                    </span>
                    <span class="ql-formats">
                        <button type="button" class="ql-script" value="sub"> </button>
                        <button type="button" class="ql-script" value="super"></button>
                    </span>
                    <span class="ql-formats">
                        <button class="ql-header" value="1" type="button"></button>
                        <button class="ql-header" value="2" type="button"> </button>
                        <button class="ql-header" value="3" type="button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-type-h3" viewBox="0 0 16 16">
                                <path d="M11.07 8.4h1.049c1.174 0 1.99.69 2.004 1.724s-.802 1.786-2.068 1.779c-1.11-.007-1.905-.605-1.99-1.357h-1.21C8.926 11.91 10.116 13 12.028 13c1.99 0 3.439-1.188 3.404-2.87-.028-1.553-1.287-2.221-2.096-2.313v-.07c.724-.127 1.814-.935 1.772-2.293-.035-1.392-1.21-2.468-3.038-2.454-1.927.007-2.94 1.196-2.981 2.426h1.23c.064-.71.732-1.336 1.744-1.336 1.027 0 1.744.64 1.744 1.568.007.95-.738 1.639-1.744 1.639h-.991V8.4ZM7.495 13V3.201H6.174v4.15H1.32V3.2H0V13h1.32V8.513h4.854V13z"/>
                            </svg>
                        </button>
                        <button type="button" class="ql-blockquote"></button>
                        <button type="button" class="ql-code-block"></button>
                    </span>
                    <span class="ql-formats">
                        <button type="button" class="ql-clean"></button>
                    </span>
                    <span class="text-success" role="button" @click="onSave" v-tooltip="'Sauvegarder'" v-if="saveButton">
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
        placeholder: {
            type: String,
            default: 'Renseignez la description'
        },
        saveButton: {
            type: Boolean,
            default: true
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

    watch: {
        content() {
            this.$emit('update:data', this.content);
        },

        data() {
            if (this.data !== this.content) {
                this.content = this.data;
            }
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
