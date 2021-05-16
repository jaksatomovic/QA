<template>
    <div class="media border-bottom p-3 p-lg-4">
        <vote-item type="answer" :model="answer"></vote-item>

        <div class="media-body">
            <a v-if="!isExtendedTemplate" :href="answer.question.path" class="text-dark">
                <h3 v-text="answer.question.title"></h3>
            </a>

            <span v-if="isAccepted" class="badge badge-danger float-right p-2">
                <i class="fas fa-check text-sm"></i> <span class="d-none d-md-inline-block">{{ __('Best answer') }}</span>
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

                <answer-accept
                    v-if="canAccept && isExtendedTemplate"
                    @accept="updateIsAccepted"
                    :id="answer.id">
                </answer-accept>

                <div v-if="isAuth" class="dropdown d-inline-block">
                    <a class="btn btn-sm btn-outline-secondary" href="#" role="button" id="canAccessLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       <i class="fas fa-ellipsis-h"></i>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="canAccessLink">
                        <a v-if="!canAccess" @click.prevent="report" class="dropdown-item" href="#">
                            {{ __('Report Answer') }}
                        </a>
                        <a v-if="canAccess" class="dropdown-item" href="#" @click.prevent="edit">
                            {{ __('Edit Answer') }}
                        </a>
                        <a v-if="canAccess" class="dropdown-item" href="#" @click.prevent="showModal">
                            {{ __('Delete Answer') }}
                        </a>
                    </div>
                </div>

                <div v-if="canAccess">
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
import AnswerAccept from './AnswerAccept.vue';
import VoteItem from './../Common/VoteItem.vue';
import Wysiwyg from './../Common/Wysiwyg.vue';

export default {
    props: {
        answer: {
            type: Object,
            required: true
        },
        isExtendedTemplate: {
            type: Boolean,
            default: false
        },
    },

    components: {
        AnswerAccept, VoteItem, Wysiwyg
    },

    data () {
        return {
            modalDelete: false,
            id: this.answer.id,
            body: this.answer.body,
            bodyHTML: this.answer.body_html,
            bodyHTMLCached: null,
            isAccepted: this.answer.is_accepted,
        }
    },

    created () {
        window.events.$on('answer-accepted', id => {
            this.isAccepted = (id == this.id);
        });
    },

    computed: {
        isInvalid () {
            return this.body.length < 3;
        },
        endPoint () {
            return '/questions/'+this.answer.question_id+'/answers/'+this.id;
        },
        canAccept () {
            return (this.canAcceptPolicy(this.answer) && this.isAccepted == false);
        },
        canAccess () {
            return (this.canAccessPolicy(this.answer));
        },
    },

    methods: {
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
            axios.patch(this.endPoint, {
                body: this.body
            }).then(response => {
                this.runToast(this.__('Success!'), response.data.message, 'success', true);
                this.bodyHTML = res.data.body_html;
                this.isEditing = false;
            }).catch(error => {
                this.runToast(this.__('Error!'), error.response.data.errors.body[0], 'danger');
            }).then(() => {
                this.stopUpdating();
            });
        },
        destroy () {
            axios.delete(this.endPoint)
            .then(response => {
                this.runToast(this.__('Success!'), response.data.message, 'success', true);
                this.$emit('deleted');
            }).catch(error => {
                this.runToast(this.__('Error!'), error.response.data.message, 'danger');
            }).then(() => {
                this.hideModal();
            });
        },
        updateIsAccepted (isAccepted) {
            this.isAccepted = isAccepted
        },
        report () {
            this.showReportModal('App\\Answer', this.id);
        },
        updateBody (newValue) {
            this.body = newValue;
        }
    }
}
</script>
