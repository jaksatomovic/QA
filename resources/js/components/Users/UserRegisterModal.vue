<template>
    <base-modal v-show="isVisible" @close="hideModal">
        <template v-slot:header>
            <p class="mb-0" v-text="__('Register new account')"></p>
        </template>

        <template v-slot:body>
            <form class="px-2 px-md-5">
                <div class="form-group">
                    <label class="mb-0" v-text="__('Your name')"></label>
                    <input v-model="name" type="text" class="form-control" :class="errors.name ? 'is-invalid' : ''"
                        :placeholder="__('Your full name')" required autofocus>
                    <span v-if="errors.name" v-text="errors.name[0]" class="invalid-feedback d-block"></span>
                </div>

                <div class="form-group">
                    <label class="mb-0" v-text="__('E-Mail Address')"></label>
                    <input v-model="email" type="email" class="form-control" :placeholder="__('E-Mail Address')"
                        :class="errors.email ? 'is-invalid' : ''" required>
                    <span v-if="errors.email" v-text="errors.email[0]" class="invalid-feedback d-block"></span>
                </div>

                <div class="form-group">
                    <label class="mb-0" v-text="__('Password')"></label>
                    <input v-model="password" type="password" :placeholder="__('Enter password')" class="form-control"
                        :class="errors.password ? 'is-invalid' : ''" required>
                    <span v-if="errors.password" v-text="errors.password[0]" class="invalid-feedback d-block"></span>
                </div>

                <div class="form-group">
                    <label class="mb-0" v-text="__('Confirm Password')"></label>
                    <input v-model="password_confirmation" type="password" :placeholder="__('Confirm Password')" class="form-control"
                        required>
                </div>
                <small class="text-muted">
                    {{ __('By creating new account you confirm that agree with our terms of use.') }}
                </small>
            </form>
        </template>

        <template v-slot:footer>
            <button @click="switchLoginModal" type="button" class="btn btn-light border">
                {{ __('Login') }}
            </button>
            <button @click="hideModal" type="button" class="btn btn-light border">
                {{ __('Cancel') }}
            </button>
            <button @click="register" :disabled="isInvalid" type="button" class="btn btn-outline-secondary btn-action px-3 px-md-5">
                <div v-if="isUpdating" class="spinner-border spinner-border-sm text-white">
                    <span class="sr-only" v-text="__('Loading...')"></span>
                </div>
                <span v-else v-text="__('Register')"></span>
            </button>
        </template>
    </base-modal>
</template>

<script type="text/javascript">
export default {
    data () {
        return {
            isVisible: false,
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            errors: {},
        }
    },

    computed: {
        isInvalid () {
            var reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/;
            return (this.name.length < 2 || (!reg.test(this.email)) || this.password.length < 5 || (this.password != this.password_confirmation));
        },
    },

    created () {
        window.events.$on('showRegisterModal', () => {
            this.showModal();
        });
    },

    methods: {
        showModal () {
            this.isVisible = true;
        },
        register () {
            this.startUpdating();
            axios.post('/register', {
                name: this.name,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
            }).then(() => {
                location.reload();
            }).catch(error => {
                this.errors = error.response.data.errors;
                this.stopUpdating();
            });
        },
        hideModal () {
            this.isVisible = false;
        },
        switchLoginModal () {
            this.hideModal();
            this.showLoginModal();
        },
    }
}
</script>
