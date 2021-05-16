<template>
    <div v-if="!isReportDeleted" class="p-4 border-bottom">
        <div class="media">
           <div class="media-body">
                <div class="mb-2">
                    <a class="badge badge-light py-2 px-3 mr-1" v-text="__(reportType)"></a>

                    <a v-show="!isReportSolved" class="badge badge-secondary py-2 px-3 mr-1 text-white" v-text="__('Unsolved')"></a>
                    <a v-show="isReportSolved" class="badge badge-light py-2 px-3 mr-1" v-text="__('Solved')"></a>

                    <a v-show="isModelBanned" class="badge badge-light py-2 px-3 mr-1" v-text="__('Banned')"></a>
                    <a v-show="isModelClosed" class="badge badge-light py-2 px-3 mr-1" v-text="__('Closed')"></a>
                    <a v-show="isModelDisapproved" class="badge badge-light py-2 px-3 mr-1" v-text="__('Disapproved')"></a>
                </div>

                <h5 v-if="report.withModel" class="d-block pt-2">
                    <a v-if="reportType == 'User'" :href="report.withModel.path"
                        target="_blank" class="text-body" v-text="report.withModel.name">
                    </a>

                    <a v-else-if="reportType == 'Answer'" :href="report.withModel.question.path"
                        target="_blank" class="text-body" v-text="report.withModel.body">
                    </a>

                    <a v-else :href="report.withModel.path"
                        target="_blank" class="text-body" v-text="report.withModel.title">
                    </a>
                </h5>
                <h5 v-else>
                    {{ __(reportType) }} {{ __('deleted') }}
                </h5>

                <p class="mb-0">
                    {{ __('Reason') }}: <span class="text-muted" v-text="report.message"></span>
                </p>

                <div>
                    <small class="d-block text-muted">
                        {{ __('Reported') }} {{ report.created_ago }} {{ __('by') }}
                        <a :href="report.user.path" target="_blank" v-text="report.user.name"></a>
                    </small>
                    <small v-if="report.is_solved == 1" class="d-block text-muted">
                        {{ __('Solved') }} {{ report.updated_ago }}
                    </small>
                </div>

                <div class="mt-2">
                    <a v-if="!isReportSolved" @click.prevent="solve" href="#"
                        class="btn btn-sm btn-outline-secondary no-underline p-0">
                        <span class="d-inline-block py-1 px-2">
                            <i class="fas fa-check"></i> {{ __('Mark as Solved') }}
                        </span>
                    </a>

                    <a @click.prevent="destroy" href="#"
                        class="btn btn-sm btn-outline-secondary no-underline p-0">
                        <span class="d-inline-block py-1 px-2">
                            <i class="fas fa-trash"></i> {{ __('Delete Report') }}
                        </span>
                    </a>

                    <user-ban
                        v-if="reportType == 'User'"
                        :user="report.withModel"
                        :report="report"
                        @solved="markReportAsSolved"
                        @deleted="markReportAsDeleted"
                        @banned="markModelAsBanned">
                    </user-ban>

                    <question-close
                        v-if="reportType == 'Question'"
                        :question="report.withModel"
                        :report="report"
                        @solved="markReportAsSolved"
                        @deleted="markReportAsDeleted"
                        @closed="markModelAsClosed">
                    </question-close>

                    <question-delete
                        v-if="reportType == 'Question'"
                        :question="report.withModel"
                        :report="report"
                        @solved="markReportAsSolved"
                        @deleted="markReportAsDeleted">
                    </question-delete>

                    <answer-delete
                        v-if="reportType == 'Answer'"
                        :answer="report.withModel"
                        :report="report"
                        @solved="markReportAsSolved"
                        @deleted="markReportAsDeleted">
                    </answer-delete>

                    <space-disapprove
                        v-if="reportType == 'Space'"
                        :topic="report.withModel"
                        :report="report"
                        @solved="markReportAsSolved"
                        @deleted="markReportAsDeleted"
                        @disapproved="markModelAsDisapproved">
                    </space-disapprove>

                    <topic-delete
                        v-if="reportType == 'Topic' || reportType == 'Space'"
                        :topic="report.withModel"
                        :report="report"
                        @solved="markReportAsSolved"
                        @deleted="markReportAsDeleted">
                    </topic-delete>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import UserBan from './ReportsItemUserBan.vue';
import QuestionClose from './ReportsItemQuestionClose.vue';
import QuestionDelete from './ReportsItemQuestionDelete.vue';
import AnswerDelete from './ReportsItemAnswerDelete.vue';
import SpaceDisapprove from './ReportsItemSpaceDisapprove.vue';
import TopicDelete from './ReportsItemTopicDelete.vue';

export default {
    props: {
        report: {
            type: Object,
            required: true
        }
    },

    components: {
        UserBan, QuestionClose, QuestionDelete,
        AnswerDelete, SpaceDisapprove, TopicDelete
    },

    data () {
        return {
            id: this.report.id,
            isReportSolved: this.report.is_solved == 1 || false,
            isReportDeleted: false,
            isModelBanned: this.report.withModel.status == 2 || false,
            isModelClosed: this.report.withModel.is_closed == 1 || false,
            isModelDisapproved: this.report.withModel.is_approved == 0 || false,
        }
    },

    computed: {
        reportType () {
            if (this.report.report_type) {
                if (this.report.report_type.replace('App\\', '') == 'Topic') {
                    return (this.report.withModel.is_space == 1) ? 'Space' : 'Topic';
                }

                return this.report.report_type.replace('App\\', '');
            }

            return null;
        }
    },

    created () {
        window.events.$on('reports-solved', () => {
            this.markReportAsSolved();
        });
    },

    methods: {
        markReportAsSolved () {
            this.isReportSolved = true;
        },
        markReportAsDeleted () {
            this.isReportDeleted = true;
        },
        markModelAsClosed () {
            this.isModelClosed = true;
        },
        markModelAsBanned () {
            this.isModelBanned = true;
        },
        markModelAsDisapproved () {
            this.isModelDisapproved = true;
        },
        solve () {
            axios.put('/admin/reports/'+this.id).then(() => {
                this.markReportAsSolved();
            });
        },
        destroy () {
            axios.delete('/admin/reports/'+this.id).then(() => {
                this.markReportAsDeleted();
            });
        }
    }
}
</script>
