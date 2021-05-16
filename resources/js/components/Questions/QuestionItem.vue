<template>
    <div v-if="!isDeleted" :class="{ 'border-bottom' : isExtendedTemplate }" class="p-3 p-lg-4">
        <div class="media">
            <vote-item :model="question" type="question"></vote-item>

            <div class="media-body">
                <div class="mb-2">
                    <a v-if="isClosed == 1" class="badge badge-secondary py-2 px-3 mr-1 text-white">
                        {{ __('Closed') }}
                    </a>
                    <a v-for="topic in topics" :href="topic.path" class="badge badge-light py-2 px-3 mr-1">
                        {{ topic.title }}
                    </a>
                </div>

                <h3>
                    <a v-if="isExtendedTemplate" :href="slug" class="text-body" v-text="title"></a>
                    <span v-else v-text="title"></span>
                </h3>

                <div v-html="bodyHTML" class="mb-2"></div>

                <div class="media my-2">
                    <div class="media-left align-self-center pr-2">
                        <base-avatar :user="question.user"></base-avatar>
                    </div>
                    <div class="media-body">
                        <p class="mb-n1 line-height-1 pt-1">
                            <a :href="question.user.path" target="_blank">
                                {{ question.user.name }}
                                <small class="text-muted">{{ question.user.points || 0 }}{{ __('p') }}</small>
                            </a>
                        </p>
                        <small class="text-muted">{{ __('Asked') }} {{ question.created_ago }}</small>
                    </div>
                </div>

                <a v-if="isExtendedTemplate" :href="slug" class="btn btn-sm btn-outline-secondary no-underline">
                    {{ question.answers_counted }} {{ __('answers') }}
                </a>

                <question-favorite
                    :id="id"
                    :is_favorited="question.is_favorited"
                    :total="question.favorites_counted">
                </question-favorite>

                <div v-if="isAuth" class="dropdown d-inline-block">
                    <a class="btn btn-sm btn-outline-secondary" href="#" role="button" id="canAccessLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-h"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="canAccessLink">
                        <a @click.prevent="share" class="dropdown-item" href="#">
                            {{ __('Share Question') }}
                        </a>
                        <a v-if="!canAccess" @click.prevent="report" class="dropdown-item" href="#">
                            {{ __('Report Question') }}
                        </a>
                        <a v-if="canAccess" @click.prevent="showEditModal" class="dropdown-item" href="#">
                            {{ __('Edit Question') }}
                        </a>
                        <a v-if="canAccess && isClosed == 0" @click.prevent="showCloseModal" class="dropdown-item" href="#">
                            {{ __('Close Question') }}
                        </a>
                        <a v-if="canAccess" @click.prevent="showDestroyModal" class="dropdown-item" href="#">
                            {{ __('Delete Question') }}
                        </a>
                    </div>
                </div>

                <div v-if="canAccess">
                    <base-modal v-show="modalEdit" @close="hideEditModal">
                        <template v-slot:header>
                            <p class="mb-0" v-text="__('Edit Question')"></p>
                        </template>

                        <template v-slot:body>
                            <form @submit.prevent="edit">
                                <div class="form-group">
                                    <v-select v-model="selectedTopics" :placeholder="__('Type or choose topics')" :options="modalTopics"
                                        label="title" :multiple="true" v-on:input="limitTopics">
                                        <template slot="option" slot-scope="option">
                                            {{ option.title }}
                                        </template>
                                    </v-select>
                                    <label for="topics">
                                        <small class="pl-1" v-text="__('Choose 1-5 topics for your question')"></small>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="title" v-text="__('Question title')"></label>
                                    <input type="text" class="form-control" v-model="title">
                                </div>

                                <div class="form-group">
                                    <label for="body" v-text="__('Question body')"></label>
                                    <textarea v-model="body" class="form-control"></textarea>
                                </div>
                            </form>
                        </template>

                        <template v-slot:footer>
                            <button @click="hideEditModal" type="button" class="btn btn-light border">
                                {{ __('Cancel') }}
                            </button>
                            <button @click="edit" :disabled="isInvalid" type="button" class="btn btn-action border">
                                <div v-if="isUpdating" class="spinner-border spinner-border-sm text-white ml-2 mr-2">
                                    <span class="sr-only" v-text="__('Loading...')"></span>
                                </div>
                                <span v-else v-text="__('Save')"></span>
                            </button>
                        </template>
                    </base-modal>

                    <base-modal v-show="modalDelete" @close="hideDestoryModal">
                        <template v-slot:header>
                            <p class="mb-0" v-text="__('Delete Question')"></p>
                        </template>

                        <template v-slot:body>
                            <p v-text="__('Are you sure that want to delete this question?')"></p>
                        </template>

                        <template v-slot:footer>
                            <button @click="hideDestoryModal" type="button" class="btn btn-light border">
                                {{ __('Cancel') }}
                            </button>
                            <button @click="destroy" type="button" class="btn btn-action border">
                                {{ __('Confirm') }}
                            </button>
                        </template>
                    </base-modal>

                    <base-modal v-show="modalClose" @close="hideCloseModal">
                        <template v-slot:header>
                            <p class="mb-0" v-text="__('Close Question')"></p>
                        </template>

                        <template v-slot:body>
                            <p class="mb-0" v-text="__('Are you sure that want to close this question?')"></p>
                            <p class="text-muted" v-text="__('When you close a question - nobody can add new answers for it.')"></p>
                        </template>

                        <template v-slot:footer>
                            <button @click="hideCloseModal" type="button" class="btn btn-light border">
                                {{ __('Cancel') }}
                            </button>
                            <button @click="close" type="button" class="btn btn-action border">
                                {{ __('Close') }}
                            </button>
                        </template>
                    </base-modal>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import QuestionFavorite from './QuestionFavorite.vue';
import VoteItem from './../Common/VoteItem.vue';

export default {
    props: {
        question: {
            type: Object,
            required: true
        },
        isExtendedTemplate: {
            type: Boolean,
            required: false
        },
    },

    components: {
        QuestionFavorite, VoteItem
    },

    computed: {
        isInvalid () {
            return (
                this.title.length < 5 || this.body.length < 5 ||
                this.title.length > 120 || this.body.length > 1000 ||
                this.selectedTopics.length < 1 || this.selectedTopics.length > 5
            );
        },
        canAccess () {
            return (this.canAccessPolicy(this.question));
        }
    },

    data () {
        return {
            modalEdit: false,
            modalDelete: false,
            modalClose: false,
            id: this.question.id,
            slug: this.question.path,
            title: this.question.title,
            topics: this.question.topics,
            body: this.question.body,
            bodyHTML: this.question.body_html,
            isClosed: this.question.is_closed,
            isDeleted: false,
            beforeEditCache: {},
            modalTopics: [],
            selectedTopics: this.question.topics,
        }
    },

    methods: {
        limitTopics(e) {
            if(e.length > 5) {
                e.pop();
            }
        },
        fetchTopics (endPoint) {
            axios.get(endPoint)
            .then(({data}) => {
                this.modalTopics.push(...data);
            });
        },
        showEditModal () {
            this.beforeEditCache = {
                title: this.title,
                body: this.body,
            }
            if (this.modalTopics.length == 0) {
               this.fetchTopics('/topics/all');
            }
            this.modalEdit = true;
        },
        hideEditModal () {
            this.modalEdit = false;
            this.title = this.beforeEditCache.title;
            this.body = this.beforeEditCache.body;
        },
        edit () {
            this.startUpdating();
            axios.put('/questions/'+this.id, {
                title: this.title,
                body: this.body,
                topics: this.selectedTopics.map(topic => {
                    return topic.id;
                }),
            }).then(res => {
                this.runToast(this.__('Success!'), res.data.message, 'success');
                this.slug = '/questions/'+res.data.slug;
                this.bodyHTML = res.data.body_html;
                this.topics = this.selectedTopics;
                if (! this.isExtendedTemplate) {
                    window.history.pushState('', this.title, '/questions/'+res.data.slug);
                }
                this.modalEdit = false;
            }).catch(error => {
                this.runToast(this.__('Error!'), error.response.data.message, 'danger');
            }).then(() => {
                this.stopUpdating();
            });
        },
        showDestroyModal () {
            this.modalDelete = true;
        },
        hideDestoryModal () {
            this.modalDelete = false;
        },
        destroy () {
            axios.delete('/questions/'+this.id)
            .then(res => {
                this.runToast(this.__('Success!'), res.data.message, 'success');
                this.$emit('deleted');
                if (this.isExtendedTemplate) {
                    this.isDeleted = true;
                } else {
                    setTimeout(() => {
                        window.location.href = '/';
                    }, 2000);
                }
            }).catch(error => {
                this.runToast(this.__('Error!'), error.response.data.message, 'danger');
            }).then(() => {
                this.hideDestoryModal();
            });
        },
        showCloseModal () {
            this.modalClose = true;
        },
        hideCloseModal () {
            this.modalClose = false;
        },
        close () {
            axios.post('/questions/'+this.id+'/close')
            .then(res => {
                this.runToast(this.__('Success!'), res.data.message, 'success');
                window.events.$emit('question-closed');
                this.isClosed = 1;
            }).catch(error => {
                this.runToast(this.__('Error!'), error.response.data.message, 'danger');
            }).then(() => {
                this.hideCloseModal();
            });
        },
        report () {
            this.showReportModal('App\\Question', this.id);
        },
        share () {
            this.showShareModal(this.question.path);
        }
    }
}
</script>
