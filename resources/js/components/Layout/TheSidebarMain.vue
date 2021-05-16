<template>
    <div class="px-3 px-md-0 pb-3">
        <div v-if="showNewQuestionBtn">
            <a @click.prevent="ask" href="#" class="btn btn-action btn-block mb-3">
                {{ __('Ask Question') }}
            </a>
        </div>
        <div v-if="showNewSpaceBtn">
            <a @click.prevent="addSpace" href="#" class="btn btn-action btn-block mb-3">
                {{ __('Create Space') }}
            </a>
        </div>

        <div class="bg-white border">
            <ul class="list-group list-group-flush font-size-14 navbar-list">
                <a v-if="isAuth" href="/questions?only=favorites" class="text-dark">
                    <li class="list-group-item py-1 pt-2">
                        <i class="fas fa-star fa-fw text-muted mr-1"></i> {{ __('Favorites') }}
                    </li>
                </a>
                <a href="/questions?only=need_answer" class="text-dark">
                    <li class="list-group-item py-1" :class="{ 'pt-2' : !isAuth }">
                        <i class="fas fa-pen fa-fw text-muted mr-1"></i> {{ __('Answer') }}
                    </li>
                </a>
                <a href="/questions" class="text-dark">
                    <li class="list-group-item py-1">
                        <i class="fas fa-infinity fa-fw text-muted mr-1"></i> {{ __('All questions') }}
                    </li>
                </a>
                <a href="/users" class="text-dark">
                    <li class="list-group-item py-1 pb-md-2">
                        <i class="fas fa-users fa-fw text-muted mr-1"></i> {{ __('All users') }}
                    </li>
                </a>
                <a href="/topics" class="d-md-none text-dark">
                    <li class="list-group-item py-1">
                        <i class="fas fa-tags fa-fw text-muted mr-1"></i> {{ __('All topics') }}
                    </li>
                </a>
                <a href="/spaces" class="d-md-none text-dark">
                    <li class="list-group-item py-1 pb-2">
                        <i class="fas fa-splotch fa-fw text-muted mr-1"></i> {{ __('Spaces') }}
                    </li>
                </a>
            </ul>
        </div>

        <div v-if="showTopics" class="d-none d-md-block mt-3 bg-white border">
            <ul class="list-group list-group-flush font-size-14 navbar-list">
                <a href="/topics" class="text-dark">
                    <li class="list-group-item py-1 pt-2">
                        <i class="fas fa-tags fa-fw text-muted mr-1"></i> {{ __('All topics') }}
                    </li>
                </a>
                <a href="/spaces" class="text-dark">
                    <li class="list-group-item py-1">
                        <i class="fas fa-splotch fa-fw text-muted mr-1"></i> {{ __('Spaces') }}
                    </li>
                </a>
                <a v-for="topic in topics" :href="topic.path" class="text-dark">
                    <li class="list-group-item py-1 text-ellipsis">
                        <base-avatar
                            :src="'/storage/'+topic.picture"
                            style="width: 20px; height: 20px;">
                        </base-avatar>
                        <span class="pl-1">{{ topic.title }}</span>
                    </li>
                </a>
            </ul>
        </div>
    </div>
</template>

<script type="text/javascript">
export default {
    props: {
        action: {
            type: String,
            default: 'add_question'
        },
    },

    data () {
        return {
            showTopics: false,
            topics: [],
        }
    },

    created () {
        var path = (this.isAuth) ? '/topics/get?followed_by='+this.authUser.id+'&limit=10' : '/topics/get?limit=10';
        this.getTopics(path);
    },

    computed: {
        showNewQuestionBtn () {
            return this.action == 'add_question';
        },
        showNewSpaceBtn () {
            return this.action == 'add_space';
        }
    },

    methods: {
        getTopics (path) {
            axios.get(path).then(({data}) => {
                this.topics.push(...data.data);
                if(this.topics.length > 0) {
                    this.showTopics = true;
                }
            });
        },
        ask () {
            this.showAskModal();
        },
        addSpace () {
            this.showAddSpaceModal();
        }
    }
}
</script>
