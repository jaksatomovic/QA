<template>
    <div v-if="!isDeleted">
        <div class="card-body p-3 border-bottom">
            <div v-show="isBanned" class="alert alert-danger border mb-3" role="alert">
                <p class="mb-0">
                    <i class="fas fa-exclamation-circle text-danger"></i>
                    {{ __('This user is banned') }}
                </p>
            </div>

            <div class="d-flex">
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

            <p class="pt-2" v-text="user.credentials"></p>

            <div class="mt-3">

                <user-ban
                    v-if="!isBanned"
                    :user="user"
                    @banned="markAsBanned">
                </user-ban>

                <user-unban
                    v-if="isBanned"
                    :user="user"
                    @unbanned="markAsUnbanned">
                </user-unban>

                <user-delete
                    :user="user"
                    @deleted="markAsDeleted">
                </user-delete>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import UserBan from './UsersListUserBan.vue';
import UserUnban from './UsersListUserUnban.vue';
import UserDelete from './UsersListUserDelete.vue';

export default {
    props: {
        user: {
            type: Object,
            required: true
        }
    },

    components: {
       UserBan, UserUnban, UserDelete
    },

    data () {
        return {
            id: this.user.id,
            name: this.user.name || '',
            email: this.user.email || '',
            credentials: this.user.credentials || '',
            location: this.user.location || '',
            about: this.user.about_html || '',
            is_banned: this.user.status == 2,
            is_deleted: 0
        }
    },

    computed: {
        isBanned () {
            return this.is_banned == true;
        },
        isDeleted () {
            return this.is_deleted == 1;
        },
    },

    methods: {
        markAsUnbanned () {
            this.is_banned = false;
        },
        markAsBanned () {
            this.is_banned = true;
        },
        markAsDeleted () {
            this.is_deleted = 1;
        }
    }
}
</script>
