<template>
    <div class="d-inline-block">

        <a @click.prevent="showDeleteModal" href="#"
            class="btn btn-sm btn-outline-secondary no-underline p-0">
            <span class="d-inline-block py-1 px-2">
                <i class="fas fa-trash"></i> {{ __('Delete Page') }}
            </span>
        </a>

        <base-modal v-show="modalDelete" @close="hideDeleteModal">
            <template v-slot:header>
                <p class="mb-0" v-text="__('Delete Page')"></p>
            </template>

            <template v-slot:body>
                <p class="mb-1">
                    {{ __('Are you sure that want to delete this page?') }}
                </p>
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
        page: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            modalDelete: false,
            id: this.page.id,
        }
    },

    methods: {
        showDeleteModal () {
            this.modalDelete = true;
        },
        hideDeleteModal () {
            this.modalDelete = false;
        },
        close () {
            axios.delete('/admin/pages/'+this.id)
            .then(response => {
                this.runToast(this.__('Success!'), response.data.message, 'success');
                this.$emit('deleted');
            }).catch(error => {
                this.runToast(this.__('Error!'), error.response.data.message, 'danger');
            }).then(() => {
                this.hideDeleteModal();
            });
        }
    }
}
</script>
