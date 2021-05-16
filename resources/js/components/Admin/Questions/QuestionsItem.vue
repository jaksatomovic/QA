<template>
    <div v-if="!isDeleted" class="p-4 border-bottom">
        <div class="media">
            <div class="media-body">
                <div class="mb-2">
                    <a v-if="isClosed" class="badge badge-secondary py-2 px-3 mr-1 text-white">
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

                <a :href="slug" class="btn btn-sm btn-outline-secondary no-underline" target="_blank">
                    {{ question.answers_counted }} {{ __('answers') }}
                </a>

                <question-close
                    v-if="!isClosed"
                    :question="question"
                    @closed="markAsClosed">
                </question-close>

                <question-edit
                    :question="question"
                    @updated="update">
                </question-edit>

                <question-delete
                    :question="question"
                    @deleted="markAsDeleted">
                </question-delete>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import QuestionClose from './QuestionsItemQuestionClose.vue';
import QuestionDelete from './QuestionsItemQuestionDelete.vue';
import QuestionEdit from './QuestionsItemQuestionEdit.vue';

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
       QuestionClose, QuestionDelete, QuestionEdit
    },

    data () {
        return {
            id: this.question.id,
            title: this.question.title,
            slug: this.question.path,
            topics: this.question.topics,
            bodyHTML: this.question.body_html,
            isClosed: this.question.is_closed == 1,
            isDeleted: false,
        }
    },

    methods: {
        markAsClosed () {
            this.isClosed = true;
        },
        markAsDeleted () {
            this.isDeleted = true;
        },
        update (data) {
            this.title = data.title;
            this.slug = data.slug;
            this.topics = data.topics;
            this.bodyHTML = data.body_html;
        }
    }
}
</script>
