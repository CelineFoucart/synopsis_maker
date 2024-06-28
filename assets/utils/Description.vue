<template>
    <article>
        <h2 class="h5" v-if="label && label.length > 0">{{ label }}</h2>
        <div :style="{'display': editMode === true ? 'block': 'none'}">
            <Editor :id="id" v-model="content" :init="editorOptions" />
        </div>
        <div :style="{'display': editMode === false ? 'block': 'none'}">
            <Editor v-model="content" :init="editorOptions" :disabled="true" :inline="true" />
        </div>
    </article>
</template>

<script>
import tinymce from 'tinymce';
import 'tinymce/icons/default/icons.min.js';
import 'tinymce/themes/silver/theme.min.js';
import 'tinymce/models/dom/model.min.js';
import 'tinymce/skins/ui/oxide/skin.js';
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/code';
import 'tinymce/plugins/emoticons';
import 'tinymce/plugins/emoticons/js/emojis';
import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/table';
import 'tinymce/plugins/wordcount';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/image';
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/preview';
import 'tinymce/plugins/nonbreaking';
import 'tinymce/plugins/visualchars';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/insertdatetime';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/quickbars';
import 'tinymce/plugins/charmap';
import './fr-FR.js';
import 'tinymce/skins/content/default/content.css';
import Editor from '@tinymce/tinymce-vue';

export default {
    name: 'Description',

    props: {
        data: {
            type: String,
            default: ''
        },
        label: {
            type: String,
            default: null
        },
        id: {
            type: String,
            default: 'content'
        },
        editMode: {
            type: Boolean,
            default: false
        }
    },

    components: {
        Editor
    },

    data() {
        return {
            content: '',
            editorOptions: { 
                element_format: 'xhtml',
                license_key: 'gpl',
                language: 'fr_FR',
                branding: false,
                promotion: false,
                link_context_toolbar: true,
                contextmenu: 'alignleft aligncenter alignright alignjustify | bold italic underline strikethrough | forecolor backcolor fontsizes | image link table | selectall cut copy paste removeformat',
                insertdatetime_formats: ['%H:%M:%S', '%d/%m/%Y', '%d/%m/%Y %H:%M:%S'],
                quickbars_insert_toolbar: 'quicktable quicklink hr bullist numlist',
                quickbars_selection_toolbar: 'bold italic underline strikethrough bullist quicklink blockquote quicktable',
                convert_urls: false,
                fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt 48pt",
                toolbar:
                    'fullscreen undo redo | blocks fontsize bold italic underline strikethrough forecolor backcolor removeformat | \
                    alignleft aligncenter alignright alignjustify outdent indent  bullist numlist | \
                    quicklink quicktable hr charmap | lineheight fontfamily | searchreplace preview code ',
                menu: {
                    file: { title: 'File', items: 'code wordcount | newdocument print ' },
                },
                highlight_on_focus: false,
                toolbar_mode: 'sliding',
                menubar: 'file view format edit insert table',
                plugins: 'advlist anchor autolink nonbreaking preview visualchars visualblocks insertdatetime searchreplace quickbars charmap lists link image table code wordcount fullscreen',
                skin_url: '/assets/oxide',
                content_css: '/assets/editor.css'
            },
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
        },
    },

    mounted () {
        this.content = this.data;
    },
}
</script>
