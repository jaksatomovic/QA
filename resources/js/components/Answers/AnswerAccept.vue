<template>
    <div class="d-inline-block">
        <a @click.prevent="showModal" href="#" class="btn btn-sm btn-outline-secondary no-underline">
            <i class="fas fa-check"></i> {{ __('Set as Best Answer') }}
        </a>

        <base-modal v-show="acceptModal" @close="hideModal">
            <template v-slot:header>
                <p class="mb-0" v-text="__('Accept answer')"></p>
            </template>

            <template v-slot:body>
                <p v-text="__('Are you sure that want to accept this answer?')"></p>
            </template>

            <template v-slot:footer>
                <button @click="hideModal" type="button" class="btn btn-light border">
                    {{ __('Cancel') }}
                </button>
                <button @click="accept" type="button" class="btn btn-action border">
                    <div v-if="isUpdating" class="spinner-border spinner-border-sm text-white">
                        <span class="sr-only" v-text="__('Loading...')"></span>
                    </div>
                    <span v-else v-text="__('Confirm')"></span>
                </button>
            </template>
        </base-modal>
    </div>
</template>

<script type="text/javascript">
export default {
    props: {
        id: {
            type: Number,
            required: true
        }
    },

    data () {
        return {
            acceptModal: false,
        }
    },

    methods: {
        showModal () {
            this.acceptModal = true;
        },
        hideModal () {
            this.acceptModal = false;
        },
        accept () {
            this.startUpdating();
            axios.post('/answers/'+this.id+'/accept')
            .then(response => {
                this.runToast(this.__('Success!'), response.data.message, 'success', true);
                this.$emit('accept', true);
                window.events.$emit('answer-accepted', this.id);
            }).catch(error => {
                this.runToast(this.__('Error!'), error.response.data.message, 'danger');
            }).then(() => {
                this.stopUpdating();
                this.hideModal();
            });
        }
    }
}
</script>
