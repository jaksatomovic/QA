<template>
    <div class="px-3 px-md-0">
        <div v-if="isAuth">
            <div v-if="hasIncompleteProfile" class="bg-white border py-4 px-3">
                <h5 class="text-center"><i class="fas fa-user-check fa-2x text-app-primary"></i></h5>
                <p class="mt-2 mb-1">{{ __('Complete your profile') }}</p>
                <ul class="pl-3 text-muted">
                    <li v-if="!authUser.credentials">{{ __('Add credentials') }}</li>
                    <li v-if="!authUser.location">{{ __('Add location') }}</li>
                    <li v-if="!authUser.about">{{ __('Add information about you') }}</li>
                    <li v-if="!authUser.picture">{{ __('Upload profile image') }}</li>
                </ul>
                <a :href="authUser.path" class="btn btn-action btn-sm btn-block" v-text="__('Complete')"></a>
            </div>
            <div class="d-none d-md-block" v-else>
                <a :href="authUser.path" class="btn btn-light bg-white border btn-block d-flex">
                    <base-avatar
                        :user="authUser"
                        class="avatar-sm"
                        style="margin-top: -0.1rem">
                    </base-avatar>
                    <div class="ml-2 text-left text-ellipsis">
                       <p v-text="authUser.name" class="mb-0 line-height-1"></p>
                       <small class="d-block mt-n1">{{ authUser.points }} {{ __('points') }}</small>
                    </div>
                </a>
            </div>

            <div v-if="question" class="bg-white border mt-3 py-2 px-3">
                <p class="mb-0" v-text="question.title"></p>
                <small class="text-muted">
                    <span>{{ __('Created') }}: {{ question.created_ago }}<br></span>
                    <span v-if="question.edited_ago">{{ __('Edited') }}: {{ question.edited_ago }}<br></span>
                    <span>{{ __('Views') }}: {{ question.views }}<br></span>
                    <span>{{ __('Answers') }}: {{ question.answers_counted }}</span>
                </small>
            </div>

            <div v-if="topic" class="bg-white border mt-3 py-2 px-3">
                <p class="mb-0">
                    <i v-if="topic.is_space == 1" class="fas fa-splotch fa-fw text-muted"></i>
                    <i v-else class="fas fa-tags fa-fw text-muted"></i>
                    {{ topic.title }}
                </p>
                <small class="text-muted">
                    <span v-if="topic.is_space == 1">
                        <span>{{ __('This is Space') }}<br></span>
                        <span>{{ __('Created') }}: {{ topic.created_ago }}<br></span>
                        <span>{{ __('Owner') }}: <a :href="topic.owner.path" target="_blank">{{ topic.owner.name }}</a><br></span>
                    </span>
                    <span v-else>
                        <span>{{ __('This is Topic') }}<br></span>
                    </span>
                </small>
            </div>

            <div v-if="!hasIncompleteProfile && !topic && !question" class="bg-white border mt-3 py-2 px-3">
                <p class="mb-0">
                    <span class="mb-1 d-block"><i class="fas fa-chart-line text-muted"></i> {{ __('Interesting') }}</span>
                    <span v-if="interestingQuestions.length > 0">
                        <a v-for="(question, index) in interestingQuestions" :key="question.id"
                        :href="question.path" class="d-block border-top py-1">
                            <span class="text-muted">{{ question.title }}</span>
                            <small class="text-dark d-block text-right">
                                <b>{{ question.views }} {{ __('views') }}</b>
                            </small>
                        </a>
                    </span>
                    <span v-if="noInterestingQuestions" class="text-muted d-block">
                        {{ __('Nothing interesting for the moment...') }}
                    </span>
                </p>

            </div>
        </div>
        <div v-else>
            <a @click.prevent="showRegisterModal" href="#" class="btn btn-action btn-block mb-3">
                {{ __('Get Started') }}
            </a>

            <div class="bg-white border py-2 px-3">
                <p class="mb-0">{{ __('Get started because...') }}</p>
                <ul class="text-muted pl-3">
                    <li>{{ __('It is free') }}</li>
                    <li>{{ __('Experience sharing') }}</li>
                    <li>{{ __('Interesting communities') }}</li>
                    <li>{{ __('A lot of features') }}</li>
                </ul>
            </div>

            <a @click.prevent="showLoginModal" href="#" class="btn btn-light bg-white border mt-3 btn-block text-left pl-3">
               <p class="mb-0" v-text="__('Have an account?')"></p>
               <small class="text-muted d-block mt-n1" v-text="__('Sign in to continue')"></small>
            </a>
        </div>

        <div class="bg-white border mt-3 py-2 px-3">
            <p v-text="copyrights" class="mb-0"></p>
            <small>
                <a v-for="(page, index) in pages"
                    :key="page.id"
                    :href="'/page/'+page.slug"
                    v-text="page.title"
                    class="d-block text-muted">
                </a>
                <a v-if="isAdmin" href="/admin/index" class="d-block text-danger">
                    <b>{{ __('Admin panel') }}</b>
                </a>
            </small>
        </div>
    </div>
</template>

<script type="text/javascript">
export default {
    props: {
        site_name: {
            type: String,
            required: true
        },
        question: {
            type: Object,
            required: false
        },
        topic: {
            type: Object,
            required: false
        }
    },

    data () {
        return {
            interestingQuestions: [],
            noInterestingQuestions: false,
            pages: [],
        }
    },

    created () {
        this.getPages();
        if (this.isAuth) {
            this.getInterestingQuestions();
        }
    },

    computed: {
        isAdmin () {
            return (this.authUser) ? this.authUser.is_admin == 1 : false;
        },
        copyrights () {
            return '© ' + new Date().getFullYear() + ' ' + this.site_name;
        },
        hasIncompleteProfile () {
            var incomplete = false;
            if (this.isAuth) {
                if (! this.authUser.credentials || ! this.authUser.location ||
                    ! this.authUser.about || ! this.authUser.picture) {
                    var incomplete = true;
                }
            }
            return incomplete;
        },
    },

    methods: {
        getInterestingQuestions () {
            axios.get('/questions/get?sort_by=interesting&limit=3').then(({data}) => {
                this.interestingQuestions.push(...data.data);
                if (this.interestingQuestions.length == 0) {
                    this.noInterestingQuestions = true;
                }
            });
        },
        getPages () {
            axios.get('/pages').then(({data}) => {
                this.pages = data;
            });
        }
    }
}
</script>
