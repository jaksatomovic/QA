<template>
    <div v-if="!isDeleted" class="p-4 border-bottom">
        <div class="media-body">
            <a :href="answer.question.path" class="text-dark" target="_blank">
                <h3 v-text="answer.question.title"></h3>
            </a>

            <span v-if="isAccepted" class="badge badge-danger float-right p-2">
                <i class="fas fa-check"></i> {{ __('Best answer') }}
            </span>

            <div class="media mb-2">
                <div class="media-left align-self-center pr-2">
                    <base-avatar :user="answer.user"></base-avatar>
                </div>
                <div class="media-body">
                    <p class="mb-n1 line-height-1 pt-1">
                        <a :href="answer.user.path" target="_blank">
                            <span>{{ answer.user.name }}</span>
                            <small class="text-muted">{{ answer.user.points || 0 }}{{ __('p') }}</small>
                        </a>
                    </p>
                    <small class="text-muted">
                        {{ __('Added') }} {{ answer.created_ago }}
                    </small>
                </div>
            </div>

            <form v-if="isEditing" @submit.prevent="update">
                <div class="form-group">
                    <wysiwyg
                        @input="updateBody"
                        :value="body">
                    </wysiwyg>
                </div>
                <button class="btn btn-sm btn-action border" :disabled="isInvalid">
                    <div v-if="isUpdating" class="spinner-border spinner-border-sm text-light ml-2 mr-2">
                        <span class="sr-only" v-text="__('Loading...')"></span>
                    </div>
                    <span v-else v-text="__('Update')"></span>
                </button>
                <button @click="cancel" type="button" class="btn btn-sm btn-light border">
                    {{ __('Cancel') }}
                </button>
            </form>
            <div v-else>
                <div v-html="bodyHTML" class="user-content mb-2 row mx-0"></div>

                <a href="#" @click.prevent="edit" class="btn btn-sm btn-outline-secondary no-underline">
                    <i class="fas fa-edit"></i> {{ __('Edit Answer') }}
                </a>

                <answer-delete
                    :answer="answer"
                    @deleted="markAsDeleted">
                </answer-delete>

                <div v-if="isEditing">
                    <base-modal v-show="modalDelete" @close="hideModal">
                        <template v-slot:header>
                            <p class="mb-0" v-text="__('Delete Answer')"></p>
                        </template>
                        <template v-slot:body>
                            <p v-text="__('Are you sure that want to delete this answer?')"></p>
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
        </div>
    </div>
</template>

<script type="text/javascript">
import Wysiwyg from './../../Common/Wysiwyg.vue';
import AnswerDelete from './AnswersItemAnswerDelete.vue';

export default {
    props: {
        answer: {
            type: Object,
            required: true
        }
    },

    components: {
       Wysiwyg, AnswerDelete
    },

    data () {
        return {
            modalDelete: false,
            id: this.answer.id,
            body: this.answer.body,
            bodyHTML: this.answer.body_html,
            bodyHTMLCached: null,
            isDeleted: false,
            isEditing: false,
            isAccepted: this.answer.is_accepted,
        }
    },

    computed: {
        isInvalid () {
            return this.body.length < 3;
        }
    },

    methods: {
        markAsDeleted () {
            this.isDeleted = true;
        },
        showModal () {
            this.modalDelete = true;
        },
        hideModal () {
            this.modalDelete = false;
        },
        edit () {
            this.bodyHTMLCached = this.body;
            this.isEditing = true;
        },
        cancel () {
            this.body = this.bodyHTMLCached;
            this.isEditing = false;
        },
        update () {
            this.startUpdating();
            axios.put('/admin/answers/'+this.id, {
                body: this.body
            }).then(response => {
                this.runToast(this.__('Success!'), response.data.message, 'success', true);
                this.bodyHTML = res.data.body_html;
            }).catch(error => {
                if (error.data) {
                    this.runToast(this.__('Error!'), error.data.errors.body[0], 'danger');
                }
            }).then(() => {
                this.stopUpdating();
                this.isEditing = false;
            });
        },
        updateBody (newValue) {
            this.body = newValue;
        }
    }
}
</script>
