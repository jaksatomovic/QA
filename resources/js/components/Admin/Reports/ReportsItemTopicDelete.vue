<template>
    <div v-if="!isDeleted" class="d-inline-block">

        <a @click.prevent="showDeleteModal" href="#"
            class="btn btn-sm btn-outline-secondary no-underline p-0">
            <span class="d-inline-block py-1 px-2">
                <i class="fas fa-times"></i> {{ __('Delete '+reportType) }}
            </span>
        </a>

        <base-modal v-show="showDelete" @close="hideDeleteModal">
            <template v-slot:header>
                <p class="mb-0" v-text="__('Delete '+reportType)"></p>
            </template>

            <template v-slot:body>
                <p class="mb-1">
                    {{ __('Are you sure that want to delete this topic / space?') }}
                </p>
                <label class="modal-label d-block mb-0">
                    <input v-model="sendNotification" type="checkbox" checked=""> {{ __('Send notification about deleted topic / space to owner') }}
                </label>
                <label class="modal-label d-block mb-0">
                    <input type="checkbox" checked="" disabled=""> {{ __('Will be also deleted report') }}
                </label>
            </template>

            <template v-slot:footer>
                <button @click="hideDeleteModal" type="button" class="btn btn-light border">
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
        report: {
            type: Object,
            required: true
        },
        topic: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            showDelete: false,
            id: this.topic.id,
            isDeleted: false,
            sendNotification: true,
            markAsSolved: true,
            markAsDeleted: false,
        }
    },

    computed: {
        reportType () {
            return (this.topic.is_space == 1) ? 'Space' : 'Topic';
        }
    },

    methods: {
        showDeleteModal () {
            this.showDelete = true;
        },
        hideDeleteModal () {
            this.showDelete = false;
        },
        close () {
            var url = '/admin/topics/'+this.id;
            url = (this.sendNotification) ? url+'?send_notification='+this.sendNotification : url;
            axios.delete(url)
            .then(response => {
                this.runToast(this.__('Success!'), response.data.message, 'success');
                this.isDeleted = true;
                axios.delete('/admin/reports/'+this.report.id).then(() => {
                    this.$emit('deleted');
                });
            }).catch(error => {
                this.runToast(this.__('Error!'), error.response.data.message, 'danger');
            }).then(() => {
                this.hideDeleteModal();
            });
        }
    }
}
</script>
