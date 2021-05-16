<template>
    <div>
        <div v-if="!isApproved && !isExtendedTemplate" class="alert card border mb-3" role="alert">
            <p class="mb-0">
                <i class="fas fa-exclamation-circle text-danger"></i>
                {{ __('This space is not approved yet. It can not be attached to question or displayed in spaces list.') }}
            </p>
        </div>

        <div v-if="isApproved || isOwner" :class="{ 'card mb-3' : !isExtendedTemplate }">
            <div :class="{ 'border-bottom' : isExtendedTemplate }" class="card-body p-3">
                <div class="d-flex">
                    <div class="mr-2">
                        <base-avatar :src="image" class="avatar-md"></base-avatar>
                    </div>
                    <div>
                        <h5 class="mb-0">
                            <a :href="topic.path" class="text-dark" v-text="title"></a>
                        </h5>
                        <small class="d-block text-muted">
                            {{ topic.questions_count || 0 }} {{ __('questions') }}. {{ followers || 0 }} {{ __('followers') }}
                        </small>
                    </div>
                </div>

                <p v-if="isExtendedTemplate" class="pt-2" v-text="body"></p>
                <div v-else class="user-content pt-2 row mx-0">
                    <span v-html="bodyHTML"></span>
                </div>

                <div class="mt-3">
                    <a v-if="isExtendedTemplate" :href="topic.path" class="btn btn-sm btn-outline-primary">
                        {{ __('View Questions') }}
                    </a>

                    <a @click.prevent="toggle" v-if="isAuth && isApproved" href="#"
                        class="btn btn-sm btn-outline-secondary no-underline p-0">
                        <span class="d-inline-block py-1 px-2">
                            <span v-if="isFollowed">
                                <i class="fas fa-star text-warning"></i> <span class="d-none d-md-inline-block">{{ __('Followed') }}</span>
                            </span>
                            <span v-else>
                                <i class="fas fa-star"></i> <span class="d-none d-md-inline-block">{{ __('Follow') }} {{ __(type) }}</span>
                            </span>
                        </span>
                        <span v-if="followers > 0" class="d-inline-block py-1 px-2 border-left border-secondary">
                            <span v-text="followers"></span>
                        </span>
                    </a>

                    <space-edit
                        v-if="canAccess && !isExtendedTemplate"
                        :topic="topic">
                    </space-edit>

                    <div v-if="isAuth" class="dropdown d-inline-block">
                        <a class="btn btn-sm btn-outline-secondary" href="#" role="button" id="canAccessLink"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-h"></i>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="canAccessLink">
                            <a @click.prevent="report" class="dropdown-item" href="#">
                                {{ __('Report') }} {{ __(type) }}
                            </a>
                            <a @click.prevent="share" class="dropdown-item" href="#">
                                {{ __('Share') }} {{ __(type) }}
                            </a>
                        </div>
                    </div>

                    <span v-if="!isAuth">
                        <a @click.prevent="askLogin" href="#" class="btn btn-sm btn-outline-secondary no-underline p-0">
                            <span class="d-inline-block py-1 px-2">
                                <i class="fas fa-star text-muted"></i> <span v-text="followers"></span>
                            </span>
                        </a>
                        <span class="pl-2 text-muted">
                            <a href="#" @click.prevent="showLoginModal">{{ __('Login') }}</a> {{ __('to follow this') }} {{ __(type) }}
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import SpaceEdit from './SpaceEdit.vue';

export default {
    props: {
        topic: {
            type: Object,
            required: true
        },
        isExtendedTemplate: {
            type: Boolean,
            default: false
        },
    },

    components: {
        SpaceEdit
    },

    data () {
        return {
            id: this.topic.id,
            title: this.topic.title,
            bodyHTML: this.topic.body_html,
            is_approved: this.topic.is_approved,
            image: '/storage/'+this.topic.picture,
            isFollowed: this.topic.is_followed,
            followers: this.topic.followers_counted,
        }
    },

    computed: {
        body () {
            return this.bodyHTML.replace(/<\/?[^>]+(>|$)/g, "");
        },
        type () {
            return (this.topic.is_space == 1) ? 'Space' : 'Topic';
        },
        endPoint () {
            return '/topics/'+this.topic.id+'/follow';
        },
        isApproved () {
            return this.is_approved == 1;
        },
        isOwner () {
            return (this.isAuth) ? this.topic.user_id == this.authUser.id : false;
        },
        canAccess () {
            return (this.canAccessPolicy(this.topic));
        }
    },

    mounted: function () {
        window.events.$on('topic-updated', (updated) => {
            this.title = updated.title ? updated.title : this.title;
            this.image = updated.image ? updated.image : this.image;
            this.bodyHTML = updated.body ? updated.body : this.bodyHTML;
            this.is_approved = (updated.is_approved == 0) ? 0 : this.is_approved;
        });
    },

    methods: {
        toggle () {
            this.isFollowed ? this.unfollow() : this.follow();
        },
        unfollow () {
            axios.delete(this.endPoint)
            .then(response => {
                this.isFollowed = false;
                this.followers = response.data.followers;
                window.events.$emit('topic-follow', {
                    followers: this.followers,
                });
            });
        },
        follow () {
            axios.post(this.endPoint)
            .then(response => {
                this.isFollowed = true;
                this.followers = response.data.followers;
                window.events.$emit('topic-follow', {
                    followers: this.followers,
                });
            });
        },
        askLogin () {
            this.runToast(this.__('Hey anonymous,'), this.__('You need to login to follow this topic!'), 'secondary');
        },
        report () {
            this.showReportModal('App\\Topic', this.id);
        },
        share () {
            this.showShareModal(this.topic.path);
        }
    },
}
</script>
