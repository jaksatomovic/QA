<template>
    <div>
        <a href="#" @click.prevent="showModal" class="btn btn-light bg-white border">
            {{ __('Create new Page') }}
        </a>

        <base-modal v-show="modalCreate" @close="hideModal">
            <template v-slot:header>
                <p class="mb-0" v-text="__('Create new Page')"></p>
            </template>

            <template v-slot:body>
                <form @submit.prevent="edit">
                    <div class="form-group mb-0">
                        <input v-model="title" type="text" class="form-control" :placeholder="__('Page\'s title')">
                        <label>
                            <small class="pl-1">
                                {{ __('Page title. One-two words title.') }}
                            </small>
                        </label>
                    </div>

                    <div class="form-group mb-0">
                        <input v-model="slug" type="text" class="form-control" :placeholder="__('Page\'s URL')">
                        <label>
                            <small class="pl-1">
                                {{ __('Page URL. It should be unique for all pages.') }}
                            </small>
                        </label>
                    </div>

                    <div class="form-group mb-0">
                        <div class="user-content">
                            <wysiwyg
                                @input="updateBody"
                                :placeholder="__('Page\'s content')">
                            </wysiwyg>
                        </div>
                        <label>
                            <small class="pl-1">
                                {{ __('Page\'s content which will be displayed.') }}
                            </small>
                        </label>
                    </div>

                    <label class="modal-label d-block mb-0 checkbox">
                        <input v-model="markAsPublished" type="checkbox" checked=""> {{ __('Mark this page as published') }}
                    </label>
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
import Wysiwyg from './../../Common/Wysiwyg.vue';

export default {
    components: {
        Wysiwyg
    },

    data () {
        return {
            modalCreate: false,
            title: '',
            slug: '',
            body: '',
            markAsPublished: true,
        }
    },

    computed: {
        isInvalid () {
            var reg = /^([a-z_-])*[a-z]+$/;
            return (
                this.title.length < 3 || this.body.length < 5 || this.slug < 3 ||
                this.title.length > 120 || this.body.length > 5000 || this.slug > 25 ||
                !reg.test(this.slug)
            );
        },
        isPublished () {
            return (this.markAsPublished) ? 1 : 0;
        }
    },

    methods: {
        showModal () {
            this.modalCreate = true;
        },
        hideModal () {
            this.modalCreate = false;
        },
        create () {
            this.startUpdating();

            let data = new FormData();
            data.append('title', this.title);
            data.append('slug', this.slug);
            data.append('body', this.body);
            data.append('is_published', this.isPublished);
            axios.post('/admin/pages', data).then(response => {
                this.$emit('created', {
                    title: this.title,
                    slug: this.slug,
                    body: this.body,
                    is_published: this.isPublished,
                })
            }).catch(error => {
                var errors = "";
                if(error.response.data.errors.title) {
                    errors += error.response.data.errors.title+"\r\n\t";
                }
                if(error.response.data.errors.slug) {
                    errors += error.response.data.errors.slug+"\r\n\t";
                }
                if(error.response.data.errors.body) {
                    errors += error.response.data.errors.body;
                }
                this.runToast(this.__('Error!'), errors, 'danger');
            }).then(() => {
                this.stopUpdating();
                this.hideEditModal();
            });
        },
        updateBody (newValue) {
            this.body = newValue;
        }
    }
}
</script>
