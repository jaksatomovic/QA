<template>
    <base-modal v-show="isVisible" @close="hideModal">
        <template v-slot:header>
            <p class="mb-0" v-text="__('Login')"></p>
        </template>

        <template v-slot:body>
            <form class="px-2 px-md-5 pt-3" @submit.prevent="login">
                <div class="form-group">
                    <label class="mb-0" v-text="__('E-Mail Address')"></label>
                    <input v-model="email" type="text" class="form-control" :placeholder="__('Email')" required autofocus>
                </div>

                <div class="form-group">
                    <label class="mb-0" v-text="__('Password')"></label>
                    <input v-model="password" type="password" class="form-control" :placeholder="__('Password')" required>
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input v-model="remember" class="form-check-input" type="checkbox" id="remember">
                        <label class="form-check-label" for="remember" v-text="__('Remember me')"></label>
                    </div>
                    <a class="btn btn-link" href="/password/reset" v-text="__('Forgot Your Password?')"></a>
                </div>
            </form>
        </template>

        <template v-slot:footer>
            <button @click="switchRegisterModal" type="button" class="btn btn-light border">
                {{ __('Register') }}
            </button>
            <button @click="hideModal" type="button" class="btn btn-light border">
                {{ __('Cancel') }}
            </button>
            <button @click="login" :disabled="isInvalid" type="button" class="btn btn-outline-secondary btn-action px-3 px-md-5">
                <div v-if="isUpdating" class="spinner-border spinner-border-sm text-white">
                    <span class="sr-only" v-text="__('Loading...')"></span>
                </div>
                <span v-else v-text="__('Login')"></span>
            </button>
        </template>
    </base-modal>
</template>

<script type="text/javascript">
export default {
    data () {
        return {
            isVisible: false,
            email: '',
            password: '',
            remember: '',
        }
    },

    computed: {
        isInvalid () {
            var reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/;
            return ((!reg.test(this.email)) || this.password.length < 5);
        },
    },

    created () {
        window.events.$on('showLoginModal', () => {
            this.showModal();
        });
    },

    methods: {
        showModal () {
            this.isVisible = true;
        },
        login () {
            this.startUpdating();
            axios.post('/login', {
                email: this.email,
                password: this.password,
                remember: this.remember,
            }).then(() => {
                location.reload();
            }).catch(error => {
                this.stopUpdating();
                this.runToast(this.__('Error!'), error.response.data.errors.email[0], 'danger');
            });
        },
        hideModal () {
            this.isVisible = false;
        },
        switchRegisterModal () {
            this.hideModal();
            this.showRegisterModal();
        },
    }
}
</script>
