<template>
    <article>
        <h2 class="h5">{{ label }}</h2>
        <jodit-editor v-model="content" :buttons="buttons" :config="config" />
    </article>
</template>

<script>
import 'jodit/build/jodit.min.css'
import { JoditEditor } from 'jodit-vue'

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
        label: String
    },

    components: {
        JoditEditor
    },

    data() {
        return {
            content: null,
            config: {
                "language": "fr"
            },
            buttons: [
                'undo', 'redo', 'source', '|', 
                'bold', 'underline', 'italic', 'strikethrough', 'eraser', '|', 
                'align', 'indent', 'outdent',  '|', 'ul', 'ol', '|', 
                'font', 'fontsize', 'brush', 'paragraph', 'lineHeight', 'superscript', 'subscript', '|', 
                'image', 'hr', 'table', 'link', 'symbols', '|',
                'cut', 'copy', 'paste', 'selectall', 'copyformat', '|', 'find', '|', 
                'spellcheck', 'preview', 'fullsize', 'print', '|', 'about'
                
            ]
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
}
</script>
