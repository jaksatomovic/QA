<template>
    <div class="mt-4">
        <form @submit.prevent="create">
            <div class="user-content">
                <wysiwyg
                    @input="updateBody"
                    :placeholder="__('Add an answer...')">
                </wysiwyg>
            </div>
            <div class="form-group p-2 bg-footer border border-top-0">
                <button @click="create" :disabled="isInvalid" type="button" class="btn btn-action border">
                    <div v-if="isUpdating" class="spinner-border spinner-border-sm text-white mx-4">
                        <span class="sr-only" v-text="__('Loading...')"></span>
                    </div>
                    <span v-else>{{ __('Add Answer') }}</span>
                </button>
                <span v-if="!isAuth" class="ml-2 text-muted">
                    {{ __('You need to sign in to add answer') }}
                </span>
            </div>
        </form>
    </div>
</template>

<script type="text/javascript">
import Wysiwyg from './../Common/Wysiwyg.vue';

export default {
    props: {
        questionId: {
            type: Number,
            required: true
        },
    },

    components: {
        Wysiwyg
    },

    data () {
        return {
            body: '',
        }
    },

    computed: {
        isInvalid () {
            return !this.isAuth || this.body.length < 5;
        },
    },

    methods: {
        create () {
            this.startUpdating();
            axios.post('/questions/'+this.questionId+'/answers', {
                body: this.body
            }).catch(error => {
                this.runToast(this.__('Error!'), error.response.data.errors.body[0], 'danger');
            }).then(({data}) => {
                this.runToast(this.__('Success!'), data.message, 'success', true);
                this.body = '';
                this.$emit('created', data.answer);
                window.events.$emit('clean-wysiwyg');
            }).then(() => {
                this.stopUpdating();
            });
        },
        updateBody (newValue) {
            this.body = newValue;
        }
    }
}
</script>

<style>
:root {
    --ck-color-base-border: #dfdfdf;
    --font-family-sans-serif: sans-serif;
}
.ck.ck-toolbar {
    padding: 6px;
}
</style>
