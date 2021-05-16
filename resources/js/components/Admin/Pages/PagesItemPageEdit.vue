<template>
    <div class="d-inline-block">

        <a @click.prevent="showEditModal" href="#"
            class="btn btn-sm btn-outline-secondary no-underline p-0">
            <span class="d-inline-block py-1 px-2">
                <i class="fas fa-edit"></i> {{ __('Edit Page') }}
            </span>
        </a>

        <base-modal v-show="modalEdit" @close="hideEditModal">
            <template v-slot:header>
                <p class="mb-0" v-text="__('Edit Page')"></p>
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
                                :value="body"
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
                        <input v-model="is_published_boolean" type="checkbox"> {{ __('Mark this page as published') }}
                    </label>
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
import Wysiwyg from './../../Common/Wysiwyg.vue';

export default {
    props: {
        page: {
            type: Object,
            required: true
        }
    },

    components: {
        Wysiwyg
    },

    data () {
        return {
            modalEdit: false,
            id: this.page.id,
            title: this.page.title,
            slug: this.page.slug,
            body: this.page.body,
            is_published_boolean: this.page.is_published == 1,
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
            return (this.is_published_boolean) ? 1 : 0;
        }
    },

    methods: {
        showEditModal () {
            this.modalEdit = true;
        },
        hideEditModal () {
            this.modalEdit = false;
        },
        edit () {
            this.startUpdating();
            axios.put('/admin/pages/' + this.id, {
                title: this.title,
                slug: this.slug,
                body: this.body,
                is_published: this.isPublished,
            }).then(response => {
                this.$emit('edited', {
                    title: this.title,
                    slug: this.slug,
                    body: this.body,
                    is_published: this.isPublished,
                });
            }).catch(error => {
                var errors = "";
                if(error.response.data.errors.title) {
                    errors = error.response.data.errors.title;
                }
                if(error.response.data.errors.slug) {
                    errors = error.response.data.errors.slug;
                }
                if(error.response.data.errors.body) {
                    errors = error.response.data.errors.body;
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
