<template>
    <div>
        <p class="mb-0">&raquo;
            <a @click.prevent="showCloseAccountModal" href="#" class="text-app-primary">
                {{ __('Close your account') }}
            </a>
        </p>

        <base-modal v-show="modalCloseAccount" @close="hideCloseAccountModal">
            <template v-slot:header>
                <p class="mb-0" v-text="__('Close your account')"></p>
            </template>

            <template v-slot:body>
                <div v-if="!isClosed">
                    <p>
                        {{ __('When you close your account, your profile page is unavailable for another users. However, questions and answers may remain.') }}
                    </p>
                    <p>
                        {{ __('Also, you will can not login into your account. If you have any questions - contact us. Enter your password to continue:') }}
                    </p>

                    <form @submit.prevent="closeAccount">
                        <div class="form-group">
                            <input type="password" class="form-control" v-model="password" :placeholder="__('Your account password')">
                            <label class="mb-0">
                                <small class="mt-0" v-text="__('Enter your account password')"></small>
                            </label>
                            <span v-if="errors.password" v-text="errors.password[0]" class="invalid-feedback d-block"></span>
                        </div>
                    </form>
                </div>
                <div v-else class="text-center">
                    <h1><i class="fas fa-user-lock fa-2x text-app-primary"></i></h1>
                    <p class="text-muted mt-4" v-text="__('Your account is closed now!')"></p>
                    <small class="text-muted" v-text="__('This dialog will be closed in 3 seconds...')"></small>
                </div>
            </template>

            <template v-slot:footer>
                <div v-if="!isClosed">
                    <button @click="hideCloseAccountModal" type="button" class="btn btn-light border">
                        {{ __('Cancel') }}
                    </button>
                    <button @click="closeAccount" :disabled="isInvalid" type="button" class="btn btn-action border">
                        <div v-if="isUpdating" class="spinner-border spinner-border-sm text-white ml-2 mr-2">
                            <span class="sr-only" v-text="__('Loading...')"></span>
                        </div>
                        <span v-else v-text="__('Close')"></span>
                    </button>
                </div>
                <div v-else>
                    <span></span>
                </div>
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
            password: '',
            modalCloseAccount: false,
            errors: {},
            isClosed: false,
        }
    },

    computed: {
        isInvalid () {
            return (
                !this.password || this.password.length < 1
            );
        },
        endPoint () {
            return '/users/id'+this.id+'/close';
        },
    },

    methods: {
        showCloseAccountModal () {
            this.modalCloseAccount = true;
        },
        hideCloseAccountModal () {
            this.modalCloseAccount = false;
            this.password = '';
        },
        closeAccount () {
            this.startUpdating();
            axios.put(this.endPoint, {
                password: this.password
            }).then(({data}) => {
                this.isClosed = true;
                setTimeout(() => {
                    axios.post('/logout').then(() => {
                        location.reload();
                    });
                }, 3000);
            }).catch(error => {
                this.errors = error.response.data.errors;
            }).then(() => {
                this.stopUpdating();
            });
        },
    }
}
</script>
