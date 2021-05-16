<template>
    <div class="d-inline-block">
        <a @click.prevent="showEditProfileModal" class="btn btn-sm btn-outline-secondary" href="#">
            {{ __('Edit Profile') }}
        </a>

        <base-modal v-show="modalEditProfile" @close="hideEditProfileModal">
            <template v-slot:header>
                <p class="mb-0" v-text="__('Edit profile')"></p>
            </template>

            <template v-slot:body>
                <form @submit.prevent="editProfile">
                    <div class="form-group">
                        <input v-model="name" type="text" class="form-control" :placeholder="__('Enter your full name')">
                        <label class="mb-0">
                            <small class="mt-0" v-text="__('Enter your full name')"></small>
                        </label>
                        <span v-if="errors.name" v-text="errors.name[0]" class="invalid-feedback d-block"></span>
                    </div>

                    <div class="form-group">
                        <input v-model="credentials" type="text" class="form-control" :placeholder="__('Enter your profile credential')">
                        <label class="mb-0">
                            <small class="mt-0" v-text="__('Enter your profile credential')"></small>
                        </label>
                        <span v-if="errors.credentials" v-text="errors.credentials[0]" class="invalid-feedback d-block"></span>
                    </div>

                    <div class="form-group">
                        <input v-model="location" type="text" class="form-control" :placeholder="__('Country / City')">
                        <label class="mb-0">
                            <small class="mt-0" v-text="__('Where are you from? Enter your country and city')"></small>
                        </label>
                    </div>

                    <div class="form-group">
                        <div class="user-content">
                            <wysiwyg
                                @input="updateAbout"
                                :value="about"
                                :placeholder="__('Tell us something about you')">
                            </wysiwyg>
                        </div>
                        <label class="mb-0">
                            <small class="mt-0" v-text="__('Tell us something about you')"></small>
                        </label>
                    </div>
                </form>
            </template>

            <template v-slot:footer>
                <button @click="hideEditProfileModal" type="button" class="btn btn-light border">
                    {{ __('Cancel') }}
                </button>
                <button @click="editProfile" :disabled="isInvalid" type="button" class="btn btn-action border">
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
import Wysiwyg from './../Common/Wysiwyg.vue';

export default {
    props: {
        user: {
            type: Object,
            required: true
        },
    },

    components: {
        Wysiwyg
    },

    data () {
        return {
            id: this.user.id,
            name: this.user.name || '',
            credentials: this.user.credentials || '',
            location: this.user.location || '',
            about: this.user.about || '',
            modalEditProfile: false,
            beforeEditCache: {},
            errors: {},
        }
    },

    computed: {
        isInvalid () {
            return (this.name.length < 2 || this.credentials.length < 5);
        },
        endPoint () {
            return '/users/id'+this.id+'/profile';
        },
    },

    methods: {
        showEditProfileModal () {
            this.beforeEditCache = {
                name: this.name,
                credentials: this.credentials,
                location: this.location,
                about: this.about,
            }
            this.modalEditProfile = true;
        },
        hideEditProfileModal () {
            this.modalEditProfile = false;
            this.name = this.beforeEditCache.name;
            this.credentials = this.beforeEditCache.credentials;
            this.location = this.beforeEditCache.location;
            this.about = this.beforeEditCache.about;
        },
        editProfile () {
            this.startUpdating();
            axios.put(this.endPoint, {
                name: this.name,
                credentials: this.credentials,
                location: this.location,
                about: this.about,
            }).then(({data}) => {
                this.runToast(this.__('Success!'), data.message, 'success');
                this.modalEditProfile = false;
                this.$emit('updated', {
                    name: this.name,
                    credentials: this.credentials,
                    location: this.location,
                    about: this.about,
                });
            }).catch(error => {
                this.errors = error.response.data.errors;
            }).then(() => {
                this.stopUpdating();
            });
        },
        updateAbout (newValue) {
            this.about = newValue;
        }
    }
}
</script>

<style type="text/css">
.ck.ck-balloon-panel {
    z-index: 99999;
}
</style>
