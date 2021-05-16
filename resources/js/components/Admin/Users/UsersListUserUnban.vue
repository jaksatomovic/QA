<template>
    <div class="d-inline-block">

        <a @click.prevent="showUnbanModal" href="#"
            class="btn btn-sm btn-outline-secondary no-underline p-0">
            <span class="d-inline-block py-1 px-2">
                <i class="fas fa-ban"></i> {{ __('Unban User') }}
            </span>
        </a>

        <base-modal v-show="modalUnban" @close="hideUnbanModal">
            <template v-slot:header>
                <p class="mb-0" v-text="__('Unban User')"></p>
            </template>

            <template v-slot:body>
                <p class="mb-1">
                    {{ __('Are you sure that want to unban') }} <a :href="user.path" target="_blank" v-text="user.name"></a>?
                </p>
                <label class="modal-label d-block mb-0">
                    <input v-model="sendNotification" type="checkbox" checked=""> {{ __('Send this user notification about unban') }}
                </label>
            </template>

            <template v-slot:footer>
                <button @click="hideUnbanModal" type="button" class="btn btn-light border">
                    {{ __('Cancel') }}
                </button>
                <button @click="ban" type="button" class="btn btn-action border">
                    {{ __('Confirm') }}
                </button>
            </template>
        </base-modal>
    </div>
</template>

<script type="text/javascript">
export default {
    props: {
        user: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            modalUnban: false,
            id: this.user.id,
            isBanned: this.user.status != 1,
            sendNotification: true,
        }
    },

    methods: {
        showUnbanModal () {
            this.modalUnban = true;
        },
        hideUnbanModal () {
            this.modalUnban = false;
        },
        ban () {
            var url = '/admin/users/'+this.id+'/unban';
            url = (this.sendNotification) ? url+'?send_notification='+this.sendNotification : url;
            axios.put(url)
            .then(response => {
                this.runToast(this.__('Success!'), response.data.message, 'success');
                this.isBanned = true;
                this.$emit('unbanned');
            }).catch(error => {
                this.runToast(this.__('Error!'), error.response.data.message, 'danger');
            }).then(() => {
                this.hideUnbanModal();
            });
        }
    }
}
</script>
