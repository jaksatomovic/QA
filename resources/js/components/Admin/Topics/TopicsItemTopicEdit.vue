<template>
    <div class="d-inline-block">
        <a @click.prevent="showEditModal" href="#" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-edit"></i> {{ __('Edit') }}
        </a>

        <base-modal v-show="modalEdit" @close="hideEditModal">
            <template v-slot:header>
                <p class="mb-0" v-text="__('Edit Topic / Space')"></p>
            </template>

            <template v-slot:body>
                <form @submit.prevent="edit">
                    <div class="form-group mb-0">
                        <div v-if="image" class="text-center my-2">
                            <img :src="image" style="max-width: 200px; max-height: 200px">
                        </div>

                        <image-upload
                            name="image"
                            :text="__('Set image')"
                            @loaded="onLoad">
                        </image-upload>

                        <label for="image">
                            <small class="pl-1">
                                {{ __('Choose space image. Recommended 1:1 ratio, max size 2MB') }}.
                            </small>
                        </label>
                    </div>

                    <div class="form-group">
                        <label v-text="__('Title')"></label>
                        <input v-model="title" type="text" class="form-control" :placeholder="__('Title')">
                    </div>

                    <div class="form-group">
                        <label v-text="__('Description')"></label>
                        <div class="user-content">
                            <wysiwyg
                                @input="updateBody"
                                :value="body"
                                :placeholder="__('Space\'s description')">
                            </wysiwyg>
                        </div>
                    </div>
                </form>
            </template>

            <template v-slot:footer>
                <button @click="hideEditModal" type="button" class="btn btn-light border">
                    {{ __('Cancel') }}
                </button>
                <button @click="edit" :disabled="isInvalid" type="button" class="btn btn-action border">
                    <div v-if="isUpdating" class="spinner-border spinner-border-sm text-white ml-2 mr-2">
                        <span class="sr-only" v-text="__('Loading...')"></span>
                    </div>
                    <span v-else v-text="__('Save')"></span>
                </button>
            </template>
        </base-modal>
    </div>
</template>

<script type="text/javascript">
import ImageUpload from './../../Common/ImageUpload.vue';
import Wysiwyg from './../../Common/Wysiwyg.vue';

export default {
    props: {
        topic: {
            type: Object,
            required: true
        },
    },

    components: {
        ImageUpload, Wysiwyg
    },

    data () {
        return {
            id: this.topic.id,
            title: this.topic.title,
            body: this.topic.body_html,
            is_approved: this.topic.is_approved,
            image: '/storage/'+this.topic.picture,
            modalEdit: false,
            beforeEditCache: {},
        }
    },

    computed: {
        isInvalid () {
            return (
                this.title.length < 3 || this.body.length < 5 || this.title.length > 120
            );
        },
    },

    methods: {
        showEditModal () {
            this.beforeEditCache = {
                title: this.title,
                body: this.body,
                image: this.image,
            }
            this.modalEdit = true;
        },
        hideEditModal () {
            this.modalEdit = false;
            this.title = this.beforeEditCache.title;
            this.body = this.beforeEditCache.body;
            this.image = this.beforeEditCache.image;
        },
        onLoad(image) {
            this.image = image.src;
            this.imageFile = image;
        },
        edit () {
            this.startUpdating();

            let data = new FormData();
            data.append('_method', 'PUT');
            data.append('title', this.title);
            data.append('body', this.body);
            if (this.imageFile) data.append('image', this.imageFile.file);

            axios.post('/admin/topics/'+this.id, data).then(res => {
                this.runToast(this.__('Success!'), res.data.message, 'success');
                this.$emit('updated', {
                    title: this.title,
                    body: this.body,
                    image: this.image,
                    is_approved: this.is_approved,
                });
                this.modalEdit = false;
            }).catch(error => {
                var errors = "";
                if(error.response.data.errors.title) {
                    errors += error.response.data.errors.title+"\r\n\t";
                }
                if(error.response.data.errors.body) {
                    errors += error.response.data.errors.body;
                }
                if(error.response.data.errors.image) {
                    errors += error.response.data.errors.image;
                }
                this.runToast(this.__('Error!'), errors, 'danger');
            }).then(() => {
                this.stopUpdating();
            });
        },
        updateBody (newValue) {
            this.body = newValue;
        }
    }
}
</script>
