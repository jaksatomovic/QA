<template>
    <div>
        <p class="mb-0">&raquo;
            <a @click.prevent="showChangePasswordModal" href="#" class="text-app-primary">
                {{ __('Change password') }}
            </a>
        </p>

        <base-modal v-show="modalChangePassword" @close="hideChangePasswordModal">
            <template v-slot:header>
                <p class="mb-0" v-text="__('Edit Password')"></p>
            </template>

            <template v-slot:body>
                <form @submit.prevent="editPassword">
                    <div class="form-group">
                        <input v-model="password.current" type="password" class="form-control" :placeholder="__('Your curent password')">
                        <label class="mb-0">
                            <small class="mt-0" v-text="__('Enter your current password')"></small>
                        </label>
                        <span v-if="errors.current" v-text="errors.current[0]" class="invalid-feedback d-block"></span>
                    </div>

                    <div class="form-group">
                        <input v-model="password.password" type="password" class="form-control" :placeholder="__('Your new password')">
                        <label class="mb-0">
                            <small class="mt-0" v-text="__('Enter your new password. Min 5 characters.')"></small>
                        </label>
                        <span v-if="errors.password" v-text="errors.password[0]" class="invalid-feedback d-block"></span>
                    </div>

                    <div class="form-group">
                        <input v-model="password.password_confirmation" type="password" class="form-control" :placeholder="__('Confirm your new password')">
                        <label class="mb-0">
                            <small class="mt-0" v-text="__('Confirm your new password')"></small>
                        </label>
                        <span v-if="errors.password_confirmation" v-text="errors.password_confirmation[0]" class="invalid-feedback d-block"></span>
                    </div>
                </form>
            </template>

            <template v-slot:footer>
                <button @click="hideChangePasswordModal" type="button" class="btn btn-light border">
                    {{ __('Cancel') }}
                </button>
                <button @click="editPassword" :disabled="isInvalid" type="button" class="btn btn-action border">
                    <div v-if="isUpdating" class="spinner-border spinner-border-sm text-white ml-2 mr-2">
                        <span class="sr-only" v-text="__('Loading...')"></span>
                    </div>
                    <span v-else v-text="__('Change')"></span>
                </button>
            </template>
        </base-modal>
    </div>
</template>

<script type="text/javascript">
export default {
    props: {
        user: {
            type: Object,
            required: true
        },
    },

    data () {
        return {
            id: this.user.id,
            password: {
                current: '',
                password: '',
                password_confirmation: '',
            },
            modalChangePassword: false,
            errors: {},
        }
    },

    computed: {
        isInvalid () {
            return (
                !this.password.current || this.password.current.length < 1 ||
                !this.password.password || this.password.password.length < 5 ||
                (this.password.password != this.password.password_confirmation)
            );
        },
        endPoint () {
            return '/users/id'+this.id+'/password';
        },
    },

    methods: {
        showChangePasswordModal () {
            this.modalChangePassword = true;
        },
        hideChangePasswordModal () {
            this.modalChangePassword = false;
            this.password = {};
        },
        editPassword () {
            this.startUpdating();
            axios.put(this.endPoint, this.password).then(({data}) => {
                this.runToast(this.__('Success!'), data.message, 'success');
                this.modalChangePassword = false;
            }).catch(error => {
                this.errors = error.response.data.errors;
            }).then(() => {
                this.stopUpdating();
            });
        },
    }
}
</script>
