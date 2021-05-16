<template>
    <a href="/notifications" class="nav-link position-relative">
        <small><i class="fas fa-bell fa-2x"></i></small>
        <span v-if="unread > 0" v-text="unread"
            class="badge badge-danger notification-badge position-absolute rounded-circle">
        </span>
    </a>
</template>

<script type="text/javascript">
export default {
    data () {
        return {
            unread: 0,
        }
    },

    created () {
        this.fetch('/notifications/check');

        window.events.$on('notifications-read', () => {
            this.unread = 0;
        });
    },

    methods: {
        fetch (endPoint) {
            axios.get(endPoint).then(({data}) => {
                this.unread = data.unread;
            });
        }
    }
}
</script>
