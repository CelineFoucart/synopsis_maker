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
import '&utils/fr-FR.js';
import 'tinymce/skins/content/default/content.css';

tinymce.init({
    selector: 'textarea',
    license_key: 'gpl',
    language: 'fr_FR',
    a_plugin_option: true,
    branding: false,
    promotion: false,
    quickbars_selection_toolbar: 'bold italic underline strikethrough bullist quicklink blockquote',
    fontsize_formats: "8pt 10pt 12pt 14pt 18pt 24pt 36pt",
    toolbar:
        'undo redo | fontsize bold italic underline strikethrough forecolor backcolor removeformat \
        alignleft aligncenter alignright alignjustify outdent indent bullist numlist \
        quicklink hr charmap | searchreplace preview fullscreen',
    convert_urls: false,
    quickbars_insert_toolbar: 'quicklink hr bullist numlist',
    highlight_on_focus: false,
    toolbar_mode: 'sliding',
    menubar: false,
    plugins: 'advlist autolink nonbreaking preview searchreplace quickbars charmap lists link wordcount fullscreen',
    skin_url: '/assets/oxide',
    content_css: '/assets/editor.css'
});
