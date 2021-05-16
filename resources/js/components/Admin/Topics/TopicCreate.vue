<template>
    <div>
        <a href="#" @click.prevent="showModal" class="btn btn-light bg-white border">
            {{ __('Create new Topic') }}
        </a>

        <base-modal v-show="modalCreate" @close="hideModal">
            <template v-slot:header>
                <p class="mb-0" v-text="__('Create new topic')"></p>
            </template>

            <template v-slot:body>
                <form>
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
                                {{ __('Choose topic image. Recommended 1:1 ratio, max size 2MB') }}.
                            </small>
                        </label>
                    </div>

                    <div class="form-group mb-0">
                        <input v-model="title" type="text" class="form-control" :placeholder="__('Topic\'s title')">
                        <label>
                            <small class="pl-1">
                                {{ __('Topic title. One-two words title.') }}
                            </small>
                        </label>
                    </div>

                    <div class="form-group mb-0">
                        <div class="user-content">
                            <wysiwyg
                                @input="updateBody"
                                :placeholder="__('Topic\'s description')">
                            </wysiwyg>
                        </div>
                        <label>
                            <small class="pl-1">
                                {{ __('Topic description. Tell users something about this topic.') }}
                            </small>
                        </label>
                    </div>
                </form>
            </template>

            <template v-slot:footer>
                <button @click="hideModal" type="button" class="btn btn-light border">
                    <span v-text="__('Close')"></span>
                </button>
                <button @click="create" :disabled="isInvalid || isUpdating"
                    type="button" class="btn btn-action border px-5">
                    <div v-if="isUpdating" class="spinner-border spinner-border-sm text-white">
                        <span class="sr-only" v-text="__('Loading...')"></span>
                    </div>
                    <span v-else v-text="__('Create')"></span>
                </button>
            </template>
        </base-modal>
    </div>
</template>

<script type="text/javascript">
import ImageUpload from './../../Common/ImageUpload.vue';
import Wysiwyg from './../../Common/Wysiwyg.vue';

export default {
    components: {
        ImageUpload, Wysiwyg
    },

    data () {
        return {
            modalCreate: false,
            title: '',
            body: '',
            image: null,
            imageFile: null,
        }
    },

    computed: {
        isInvalid () {
            return (
                this.title.length < 3 || this.body.length < 5 ||
                this.title.length > 120 || this.body.length > 5000 ||
                ! this.image
            );
        },
    },

    methods: {
        showModal () {
            this.modalCreate = true;
        },
        hideModal () {
            this.modalCreate = false;
        },
        onLoad(image) {
            this.image = image.src;
            this.imageFile = image;
        },
        create () {
            this.startUpdating();

            let data = new FormData();
            data.append('title', this.title);
            data.append('body', this.body);
            data.append('image', this.imageFile.file);

            axios.post('/admin/topics', data).then(res => {
                setTimeout(() => {
                    window.location.href = '/topics/'+res.data.slug;
                }, 500);
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
                this.stopUpdating();
            });
        },
        updateBody (newValue) {
            this.body = newValue;
        }
    }
}
</script>
