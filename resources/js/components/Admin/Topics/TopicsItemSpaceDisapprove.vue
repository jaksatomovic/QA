<template>
    <div v-if="!isDisapproved" class="d-inline-block">

        <a @click.prevent="showDisapproveModal" href="#"
            class="btn btn-sm btn-outline-secondary no-underline p-0">
            <span class="d-inline-block py-1 px-2">
                <i class="fas fa-eye-slash"></i> {{ __('Disapprove Space') }}
            </span>
        </a>

        <base-modal v-show="modalClose" @close="hideDisapproveModal">
            <template v-slot:header>
                <p class="mb-0" v-text="__('Disapprove Space')"></p>
            </template>

            <template v-slot:body>
                <p class="mb-1">
                    {{ __('Are you sure that want to disapprove this space?') }}
                </p>
                <label class="modal-label d-block mb-0">
                    <input v-model="sendNotification" type="checkbox" checked=""> {{ __('Send notification about disapproved space to owner') }}
                </label>
            </template>

            <template v-slot:footer>
                <button @click="hideDisapproveModal" type="button" class="btn btn-light border">
                    {{ __('Cancel') }}
                </button>
                <button @click="close" type="button" class="btn btn-action border">
                    {{ __('Confirm') }}
                </button>
            </template>
        </base-modal>
    </div>
</template>

<script type="text/javascript">
export default {
    props: {
        topic: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            modalClose: false,
            id: this.topic.id,
            isDisapproved: this.topic.is_approved == 0,
            sendNotification: true,
        }
    },

    methods: {
        showDisapproveModal () {
            this.modalClose = true;
        },
        hideDisapproveModal () {
            this.modalClose = false;
        },
        close () {
            var url = '/admin/topics/'+this.id+'/disapprove';
            url = (this.sendNotification) ? url+'?send_notification='+this.sendNotification : url;
            axios.put(url)
            .then(response => {
                this.runToast(this.__('Success!'), response.data.message, 'success');
                this.isDisapproved = true;
                this.$emit('disapproved');
            }).catch(error => {
                this.runToast(this.__('Error!'), error.response.data.message, 'danger');
            }).then(() => {
                this.hideDisapproveModal();
            });
        }
    }
}
</script>
