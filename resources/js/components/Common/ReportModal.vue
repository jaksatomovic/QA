<template>
    <base-modal v-show="isVisible" @close="hideModal">
        <template v-slot:header>
            <p class="mb-0" v-text="__('Report')"></p>
        </template>

        <template v-slot:body>
            <form>
                <div class="form-group">
                    <label class="mb-0">
                        {{ __('Reason') }}:
                    </label>
                    <textarea v-model="message" class="form-control"
                        :placeholder="__('Reason for reporting')" required autofocus>
                    </textarea>
                </div>
            </form>
        </template>

        <template v-slot:footer>
            <button @click="hideModal" type="button" class="btn btn-light border">
                {{ __('Cancel') }}
            </button>
            <button @click="report" :disabled="isInvalid"
                type="button" class="btn btn-outline-secondary btn-action px-5">
                <div v-if="isUpdating" class="spinner-border spinner-border-sm text-white">
                    <span class="sr-only" v-text="__('Loading...')"></span>
                </div>
                <span v-else v-text="__('Report')"></span>
            </button>
        </template>
    </base-modal>
</template>

<script type="text/javascript">
export default {
    data () {
        return {
            isVisible: false,
            message: '',
            model_type: null,
            model_id: null,
        }
    },

    computed: {
        isInvalid () {
            return (this.message.length < 5);
        }
    },

    created () {
        window.events.$on('showReportModal', (model_type, model_id) => {
            this.model_type = model_type,
            this.model_id = model_id,
            this.showModal();
        });
    },

    methods: {
        showModal () {
            this.message = '';
            this.isVisible = true;
        },
        report () {
            this.startUpdating();
            axios.post('/reports', {
                report_id: this.model_id,
                report_type: this.model_type,
                message: this.message,
            }).then(response => {
                this.runToast(this.__('Success!'), response.data.message, 'success');
                this.hideModal();
            }).catch(error => {
                this.runToast(this.__('Error!'), error.data.errors.email[0], 'danger');
                this.hideModal();
            });
        },
        hideModal () {
            this.stopUpdating();
            this.isVisible = false;
        }
    }
}
</script>
