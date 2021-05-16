<template>
    <div class="card-body p-3" :class="{ 'border-bottom' : isExtendedTemplate }">
        <div v-if="isExtendedTemplate" class="d-flex">
            <div class="mr-2">
                <base-avatar :user="user" class="avatar-md"></base-avatar>
            </div>
            <div>
                <h5 class="mb-0">
                    <a :href="user.path" class="text-dark">
                        {{ user.name }}
                        <small class="text-muted">{{ user.points || 0  }}{{ __('p') }}</small>
                    </a>
                </h5>
                <small class="d-block text-muted">
                    {{ user.questions_count || 0  }} {{ __('questions') }}. {{ user.answers_count || 0  }} {{ __('answers') }}
                </small>
            </div>
        </div>
        <div v-else>
            <span class="badge badge-danger float-right p-2">
                {{ user.points || 0  }} {{ __('points') }}
            </span>
            <h4 class="mb-0">
                <a :href="user.path" class="text-dark" v-text="name"></a>
            </h4>
            <small v-if="tab == 'about'" class="text-muted">
                <span v-if="location">{{ __('From') }} {{ location }}. </span>{{ __('Registered') }} {{ user.created_ago }}.
            </small>
            <p v-if="credentials" v-text="credentials"></p>
            <div v-if="tab == 'about'" class="user-content row mx-0">
                <p v-if="about" class="pt-1" v-html="about"></p>
                <p v-else class="text-muted pt-1">
                    {{ __('This user did not add anything about him...') }}
                </p>
            </div>
        </div>

        <div>
            <div v-if="isExtendedTemplate" class="mt-2">
                <p class="text-muted mt-1 mb-0" v-text="credentials"></p>
                <a :href="user.path" class="btn btn-sm btn-outline-primary mt-1">
                    {{ __('View Profile') }}
                </a>
            </div>

            <div v-if="tab == 'about'" class="mt-3">
                <span v-if="!isExtendedTemplate && ownProfile">
                    <edit-profile :user="user" @updated="updateProfile"></edit-profile>
                    <edit-account :user="user"></edit-account>
                </span>
                <div v-if="isAuth && !ownProfile" class="dropdown d-inline-block">
                    <a class="btn btn-sm btn-outline-secondary" href="#" role="button" id="canAccessLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-h"></i>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="canAccessLink">
                        <a @click.prevent="share" class="dropdown-item" href="#">
                            {{ __('Share User') }}
                        </a>
                        <a @click.prevent="report" class="dropdown-item" href="#">
                            {{ __('Report User') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import EditAccount from './UserEditAccount.vue';
import EditProfile from './UserEditProfile.vue';

export default {
    props: {
        user: {
            type: Object,
            required: true
        },
        isExtendedTemplate: {
            type: Boolean,
            default: false
        },
        tab: {
            type: String,
            required: false
        },
    },

    components: {
        EditAccount, EditProfile
    },

    computed: {
        ownProfile () {
            return (this.authUser) ? this.authUser.id == this.user.id : false;
        },
    },

    data () {
        return {
            id: this.user.id,
            name: this.user.name || '',
            email: this.user.email || '',
            credentials: this.user.credentials || '',
            location: this.user.location || '',
            about: this.user.about_html || '',
        }
    },

    methods: {
        updateProfile (new_values) {
            this.name = new_values.name;
            this.credentials = new_values.credentials;
            this.location = new_values.location;
            this.about = new_values.about;
        },
        report () {
            this.showReportModal('App\\User', this.id);
        },
        share () {
            this.showShareModal(this.user.path);
        }
    }
}
</script>
