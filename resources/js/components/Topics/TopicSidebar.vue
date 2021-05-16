<template>
    <div class="px-3 px-md-0 pb-3">
        <div class="mb-2">
            <img :src="image" class="img-thumbnail rounded">
        </div>

        <a v-if="isApproved" @click.prevent="ask" href="#" class="btn btn-action btn-block mb-3">
            {{ __('Ask Question') }}
        </a>

        <div class="bg-white border">
            <ul class="list-group list-group-flush font-size-14 navbar-list">
                <a v-for="link in links" :href="link.href" class="text-dark">
                    <li class="list-group-item py-1" :class="{ 'active' : current_tab == link.tab }">
                        {{ __(link.title) }}
                        <span v-if="link.counter" class="text-muted float-right" v-text="link.counter"></span>
                    </li>
                </a>
            </ul>
        </div>
    </div>
</template>

<script type="text/javascript">
export default {
    props: {
        topic: {
            type: Object,
            required: true
        },
        tab: {
            type: String,
            default: true
        },
    },

    data () {
        return {
            current_tab: this.tab,
            image: '/storage/'+this.topic.picture,
            is_approved: this.topic.is_approved,
            links: [
                {
                    tab: 'questions',
                    title: 'Questions',
                    counter: this.topic.questions_count,
                    href: '?tab=questions',
                },
                {
                    tab: 'followers',
                    title: 'Followers',
                    counter: this.topic.followers_counted,
                    href: '?tab=followers',
                },
                {
                    tab: 'all',
                    title: 'View Topics',
                    counter: null,
                    href: '/topics',
                },
                {
                    tab: 'all',
                    title: 'View Spaces',
                    counter: null,
                    href: '/spaces',
                },
            ]
        }
    },

    computed: {
        isApproved () {
            return this.is_approved == 1;
        }
    },

    mounted: function () {
        window.events.$on('topic-updated', (updated) => {
            this.image = updated.image ? updated.image : this.image;
            this.is_approved = (updated.is_approved == 0) ? 0 : this.is_approved;
        });
        window.events.$on('topic-follow', (updated) => {
            this.links[1].counter = updated.followers ? updated.followers : '0';
        });
    },

    methods: {
        ask () {
            this.showAskModal(this.topic);
        },
    }
}
</script>
