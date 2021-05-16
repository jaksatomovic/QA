<template>
    <div v-if="!isDeleted" class="media border-bottom px-4 py-3" :class="{ 'unread-notification' : ! readed }">
        <div class="media-body">
            <div class="media">
                <div class="media-body">
                    <a :href="url" @click="markAsRead(notification.id)">
                        <p class="my-2" v-text="message"></p>
                    </a>
                    <small>
                        <i class="fa fa-clock text-muted"></i> {{ notification.created_ago }}
                    </small>
                </div>

                <a class="btn btn-sm btn-outline-secondary mt-2 ml-2" href="#" @click.prevent="showModal">
                    <i class="fa fa-trash"></i>
                </a>
            </div>

            <base-modal v-show="modalDelete" @close="hideModal">
                <template v-slot:header>
                    <p class="mb-0" v-text="__('Delete notification')"></p>
                </template>
                <template v-slot:body>
                    <p v-text="__('Are you sure that want to delete this notification?')"></p>
                </template>
                <template v-slot:footer>
                    <button @click="hideModal" type="button" class="btn btn-light border">
                        {{ __('Cancel') }}
                    </button>
                    <button @click="destroy" type="button" class="btn btn-action border">
                        {{ __('Confirm') }}
                    </button>
                </template>
            </base-modal>
        </div>
    </div>
</template>

<script type="text/javascript">
export default {
    props: {
        notification: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            readed: this.notification.read_at || null,
            modalDelete: false,
            isDeleted: false,
        }
    },

    created () {
        window.events.$on('notifications-read', () => {
            this.readed = true;
        });
    },

    computed: {
        url () {
            return JSON.parse(this.notification.data)['url'];
        },
        message () {
            return JSON.parse(this.notification.data)['message'];
        }
    },

    methods: {
        showModal () {
            this.modalDelete = true;
        },
        hideModal () {
            this.modalDelete = false;
        },
        destroy () {
            axios.delete('/notifications/'+this.notification.id+'/delete')
            .then(res => {
                this.runToast(this.__('Success!'), res.data.message, 'success');
                this.isDeleted = true;
                this.$emit('deleted');
            }).catch(error => {
                this.runToast(this.__('Error!'), error.response.data.message, 'danger');
            }).then(() => {
                this.hideModal();
            });
        },
        markAsRead (id) {
            axios.put('/notifications/'+id+'/read').then(() => {});
        }
    }
}
</script>
