<template>
    <ckeditor
        v-model="body"
        :editor="editor"
        :config="editorConfig"
        @input="$emit('input', body)">
    </ckeditor>
</template>

<script type="text/javascript">
import CKEditor from '@ckeditor/ckeditor5-vue';
Vue.use(CKEditor);
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

export default {
    props: {
        value: {
            type: String,
            required: false,
            default: '',
        },
        placeholder: {
            type: String,
            required: false,
            default: '...',
        }
    },

    created () {
        window.events.$on('clean-wysiwyg', () => {
            this.body = '';
        });
    },

    data () {
        return {
            body: this.value,
            editor: ClassicEditor,
            editorConfig: {
                placeholder: this.placeholder,
                removePlugins: [
                    'Heading'
                ],
                toolbar: [
                    'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote', '|',
                    'link', 'imageUpload', 'insertTable', '|',
                    'undo', 'redo'
                ],
                image: {
                    toolbar: [
                        'imageTextAlternative', '|', 'imageStyle:alignLeft',
                        'imageStyle:full', 'imageStyle:alignRight'
                    ],
                    styles: [
                        'full',
                        'alignLeft',
                        'alignRight'
                    ]
                },
                ckfinder: {
                    uploadUrl: '/upload-image?_token='+window.axios.defaults.headers.common['X-CSRF-TOKEN'],
                    options: {
                        resourceType: 'Images'
                    }
                }
            }
        }
    }
}
</script>
