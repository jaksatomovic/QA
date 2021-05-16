<template>
    <div>
        <answer-new
            v-if="isExtendedTemplate && !questionIsClosed"
            :question-id="question.id"
            @created="add"
            class="mb-3">
        </answer-new>

        <div class="mb-3 d-flex justify-content-between">
            <div v-if="isExtendedTemplate" class="btn bg-white border">
                {{ total }} {{ __('answers') }}
            </div>
            <div v-else>
                <span></span>
            </div>

            <base-sorts
                :filter_by="current_sort"
                :filters="sorts"
                custom_class="dropdown-menu-right"
                @changed="update_sorts">
            </base-sorts>
        </div>

        <div class="bg-white">
            <div class="card">
                <div class="card-body p-0">
                    <answer-item
                        v-for="(answer, index) in answers"
                        :key="answer.id"
                        :answer="answer"
                        @deleted="remove(index)"
                        :is-extended-template="isExtendedTemplate">
                    </answer-item>

                    <div v-if="nextUrl && !isUpdating" class="text-center my-3">
                        <button @click.prevent="fetch(nextUrl)" class="btn btn-sm btn-outline-secondary">
                            {{ __('View more answers') }}
                        </button>
                    </div>

                    <base-loading v-if="isUpdating"></base-loading>

                    <div v-show="noAnswersMessage && !isUpdating" class="text-center py-4">
                        <h1><i class="fas fa-haykal fa-2x text-app-primary"></i></h1>
                        <p class="text-muted mt-4">
                            {{ __('Sorry') }}, <br> {{ __('Seems that there are no answers yet...') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import AnswerNew from './AnswerNew.vue';
import AnswerItem from './AnswerItem.vue';
import BaseSorts from './../Common/BaseFilters.vue';

export default {
    props: {
        startPoint: {
            type: String,
            required: false
        },
        isExtendedTemplate: {
            type: Boolean,
            default: false
        },
        question: {
            type: Object,
            required: false
        }
    },

    mounted: function () {
        window.events.$on('question-closed', (updated) => {
            this.questionIsClosed = true;
        });
    },

    components: {
        AnswerNew, AnswerItem, BaseSorts
    },

    data () {
        return {
            total: 0,
            answers: [],
            nextUrl: null,
            noAnswersMessage: false,
            endPoint: this.startPoint,
            questionIsClosed: false,
            current_sort: {
                title: 'Most Rated',
                type: 'most_rated',
            },
            sorts: [
                {
                    title: 'Latest',
                    type: 'latest',
                },
                {
                    title: 'Most Rated',
                    type: 'most_rated',
                },
                {
                    title: 'Oldest',
                    type: 'oldest',
                }
            ],
        }
    },

    created () {
        if (this.question) {
            this.total = this.question.answers_counted;
            this.questionIsClosed = this.question.is_closed == 1;
            if (this.question.best_answer_id) {
                this.fetchBestAnswer();
            }
        } else {
            this.total = 1;
        }
        this.fetch(this.endPoint);
    },

    methods: {
        fetchBestAnswer () {
            this.endPoint = this.addParameterToURL(this.startPoint, 'exclude='+this.question.best_answer_id);
            this.startUpdating();
            axios.get('/answers/'+this.question.best_answer_id)
            .then(({data}) => {
                this.answers.push(data);
                this.stopUpdating();
            });
        },
        fetch (endPoint) {
            this.startUpdating();
            axios.get(endPoint).then(({data}) => {
                this.answers.push(...data.data);
                this.nextUrl = data.next_page_url;
                this.stopUpdating();
                this.noAnswersMessage = (this.answers.length == 0);
            });
        },
        add (answer) {
            this.answers.push(answer);
            this.total++;
            window.scrollTo(0, document.body.scrollHeight);
            this.noAnswersMessage = false;
        },
        remove (index) {
            this.answers.splice(index, 1);
            this.total--;
            this.noAnswersMessage = (this.answers.length == 0);
        },
        update_sorts (sort) {
            this.current_sort = sort;
            this.answers = [];
            this.fetch(this.addParameterToURL(this.endPoint, 'order_by='+sort.type));
        }
    }
}
</script>
