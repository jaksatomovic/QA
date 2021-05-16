<template>
    <div class="px-3 px-md-0 pb-3">
        <user-avatar :user="user"></user-avatar>

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
import UserAvatar from './UserEditAvatar.vue';

export default {
    props: {
        user: {
            type: Object,
            required: true
        },
        tab: {
            type: String,
            default: true
        },
    },

    components: {
        UserAvatar
    },

    data () {
        return {
            current_tab: this.tab,
            links: [
                {
                    tab: 'about',
                    title: 'About',
                    counter: null,
                    href: this.user.path,
                },
                {
                    tab: 'questions',
                    title: 'Questions',
                    counter: this.user.questions_count,
                    href: this.user.path+'?tab=questions',
                },
                {
                    tab: 'answers',
                    title: 'Answers',
                    counter: this.user.answers_count,
                    href: this.user.path+'?tab=answers',
                },
                {
                    tab: 'topics',
                    title: 'Topics',
                    counter: this.user.follow_topics_count,
                    href: this.user.path+'?tab=topics',
                },
                {
                    tab: 'spaces',
                    title: 'Spaces',
                    counter: this.user.follow_spaces_count,
                    href: this.user.path+'?tab=spaces',
                }
            ]
        }
    }
}
</script>
