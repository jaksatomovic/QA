<template>
    <div class="d-inline-block">
        <a @click.prevent="showEditAccountModal" class="btn btn-sm btn-outline-secondary" href="#">
            {{ __('Edit Account') }}
        </a>

        <base-modal v-show="modalEditAccount" @close="hideEditAccountModal">
            <template v-slot:header>
                <p class="mb-0" v-text="__('Edit Account')"></p>
            </template>

            <template v-slot:body>
                <form @submit.prevent="editAccount">
                    <div class="form-group">
                        <input type="email" class="form-control" v-model="email" :placeholder="__('Enter your email addess')">
                        <label class="mb-0">
                            <small class="mt-0" v-text="__('Enter your email addess')"></small>
                        </label>
                        <span v-if="errors.email" v-text="errors.email[0]" class="invalid-feedback d-block"></span>
                    </div>

                    <div class="form-group">
                        <select class="form-control" v-model="email_notifications">
                            <option value="0" :selected="email_notifications == 0">
                                {{ __('Do not receive email notifications') }}
                            </option>
                            <option value="1" :selected="email_notifications == 1">
                                {{ __('Receive email notifications') }}
                            </option>
                        </select>
                        <label class="mb-0">
                            <small class="mt-0" v-text="__('Do you want to receive notifications to email?')"></small>
                        </label>
                    </div>

                    <div class="form-group">
                        <change-password :user="user"></change-password>
                        <close-account :user="user"></close-account>
                    </div>
                </form>
            </template>

            <template v-slot:footer>
                <button @click="hideEditAccountModal" type="button" class="btn btn-light border">
                    {{ __('Cancel') }}
                </button>
                <button @click="editAccount" :disabled="isInvalid" type="button" class="btn btn-action border" >
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
import ChangePassword from './UserChangePassword.vue';
import CloseAccount from './UserCloseAccount.vue';

export default {
    props: {
        user: {
            type: Object,
            required: true
        },
    },

    components: {
        ChangePassword, CloseAccount
    },

    computed: {
        isInvalid () {
            var reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,24}))$/;
            return (!reg.test(this.email));
        },
        endPoint () {
            return '/users/id'+this.id+'/account';
        },
    },

    data () {
        return {
            id: this.user.id,
            email: this.user.email || '',
            email_notifications: this.user.email_notifications,
            modalEditAccount: false,
            beforeEditCache: {},
            errors: {},
        }
    },

    methods: {
        showEditAccountModal () {
            this.beforeEditCache = {
                email: this.email,
                email_notifications: this.email_notifications,
            }
            this.modalEditAccount = true;
        },
        hideEditAccountModal () {
            this.modalEditAccount = false;
            this.email = this.beforeEditCache.email;
            this.email_notifications = this.beforeEditCache.email_notifications;
        },
        editAccount () {
            this.startUpdating();
            axios.put(this.endPoint, {
                email: this.email,
                email_notifications: this.email_notifications,
            }).then(({data}) => {
                this.runToast(this.__('Success!'), data.message, 'success');
                this.modalEditAccount = false;
            }).catch(error => {
                this.errors = error.response.data.errors;
            }).then(() => {
                this.stopUpdating();
            });
        },
    }
}
</script>
