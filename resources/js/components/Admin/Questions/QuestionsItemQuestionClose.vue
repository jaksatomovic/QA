<template>
    <div v-if="!isClosed" class="d-inline-block">

        <a @click.prevent="showCloseModal" href="#"
            class="btn btn-sm btn-outline-secondary no-underline p-0">
            <span class="d-inline-block py-1 px-2">
                <i class="fas fa-lock"></i> {{ __('Close Question') }}
            </span>
        </a>

        <base-modal v-show="modalClose" @close="hideCloseModal">
            <template v-slot:header>
                <p class="mb-0" v-text="__('Close Question')"></p>
            </template>

            <template v-slot:body>
                <p class="mb-1">
                    {{ __('Are you sure that want to close this question?') }}
                </p>
                <label class="modal-label d-block mb-0">
                    <input v-model="sendNotification" type="checkbox" checked=""> {{ __('Send notification about closed question to author') }}
                </label>
            </template>

            <template v-slot:footer>
                <button @click="hideCloseModal" type="button" class="btn btn-light border">
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
        question: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            modalClose: false,
            id: this.question.id,
            isClosed: this.question.is_closed == 1,
            sendNotification: true,
        }
    },

    methods: {
        showCloseModal () {
            this.modalClose = true;
        },
        hideCloseModal () {
            this.modalClose = false;
        },
        close () {
            var url = '/admin/questions/'+this.id+'/close';
            url = (this.sendNotification) ? url+'?send_notification='+this.sendNotification : url;
            axios.put(url)
            .then(response => {
                this.runToast(this.__('Success!'), response.data.message, 'success');
                this.isClosed = true;
                this.$emit('closed');
            }).catch(error => {
                this.runToast(this.__('Error!'), error.response.data.message, 'danger');
            }).then(() => {
                this.hideCloseModal();
            });
        }
    }
}
</script>
