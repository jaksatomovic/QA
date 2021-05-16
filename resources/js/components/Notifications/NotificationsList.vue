<template>
    <div>
        <div class="mb-3 d-flex justify-content-between">
            <div>
                <span></span>
            </div>

            <div>
                <a href="#" @click.prevent="markAllAsRead" class="btn btn-light bg-white border">
                    <small><i class="fa fa-check text-muted mr-1"></i></small> {{ __('Mark all as read') }}
                </a>
            </div>
        </div>

        <div v-if="hasNotifications" class="bg-white">
            <div class="card">
                <div class="card-body p-0">
                    <notification-item
                        v-for="(notification, index) in notifications"
                        :key="notification.id"
                        :notification="notification">
                    </notification-item>

                    <div v-if="nextUrl && !isUpdating" class="text-center my-3">
                        <button @click.prevent="fetch(nextUrl)" class="btn btn-sm btn-outline-secondary">
                            {{ __('View more notifications') }}
                        </button>
                    </div>

                    <base-loading v-if="isUpdating"></base-loading>

                    <div v-if="noNotificationsMessage" class="text-center py-4">
                        <h1><i class="fas fa-haykal fa-2x text-app-primary"></i></h1>
                        <p class="text-muted mt-4">{{ __('There are not more notifications...') }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="bg-white p-3 border">
            <p class="m-0">{{ __('No notifications yet...') }}</p>
        </div>
    </div>
</template>

<script type="text/javascript">
import NotificationItem from './NotificationItem.vue';

export default {
    props: {
        startPoint: {
            type: String,
            required: false
        },
    },

    components: {
        NotificationItem
    },

    data () {
        return {
            notifications: [],
            nextUrl: null,
            hasNotifications: false,
            noNotificationsMessage: false,
            endPoint: this.startPoint,
        }
    },

    created () {
        this.fetch(this.endPoint);
    },

    methods: {
        fetch (endPoint) {
            this.startUpdating();
            axios.get(endPoint).then(({data}) => {
                this.notifications.push(...data.data);
                this.nextUrl = data.next_page_url;
                this.stopUpdating();
                if (this.notifications.length > 0) {
                    this.hasNotifications = true;
                }
            });
        },
        remove (index) {
            this.notifications.splice(index, 1);
            this.total--;
        },
        markAllAsRead () {
            axios.put('/notifications/read').then(() => {
                window.events.$emit('notifications-read');
            });
        }
    }
}
</script>
