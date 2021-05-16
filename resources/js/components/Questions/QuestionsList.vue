<template>
    <div>
        <div class="mb-3 d-flex justify-content-between">
            <base-filters
                :filter_by="current_filter"
                :filters="filters"
                @changed="update_filters">
            </base-filters>

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
                    <question-item
                        v-for="(question, index) in questions"
                        :key="question.id"
                        :question="question"
                        @deleted="remove(index)"
                        :is-extended-template="true">
                    </question-item>
                    <div v-if="nextUrl && !isUpdating" class="text-center my-3">
                        <button @click.prevent="fetch(nextUrl)" class="btn btn-sm btn-outline-secondary">
                            {{ __('View more questions') }}
                        </button>
                    </div>

                    <base-loading v-if="isUpdating"></base-loading>

                    <div v-if="noQuestionsMessage" class="text-center py-4">
                        <div v-if="current_sort.type == 'trending'">
                            <h1><i class="fas fa-chart-line fa-2x text-app-primary"></i></h1>
                            <p class="text-muted mt-4">
                                {{ __('Nothing trending for last week') }}
                                <br>
                                {{ __('But you can view all-time trending questions by filtering by \'Most Popular\'') }}.
                            </p>
                        </div>
                        <div v-else>
                            <div v-if="isFeed">
                                <h1><i class="fas fa-align-center fa-2x text-app-primary"></i></h1>
                                <p class="mt-4">{{ __('Your feed is empty') }}</p>
                                <div class="mx-5">
                                    <p class="text-muted">
                                        {{ __('We build your feed based on topics or spaces which you are following') }}.<br>
                                        {{ __('Please, choose some topics or spaces which are interesting for you and follow them') }}.<br>
                                        {{ __('Then come back to your feed - we will add some interesting content for you') }}.
                                    </p>
                                    <a href="/topics" class="btn btn-action mr-3">{{ __('Browse Topics') }}</a>
                                    {{ __('or') }}
                                    <a href="/spaces" class="btn btn-action ml-3">{{ __('Browse Spaces') }}</a>
                                </div>
                            </div>
                            <div v-else>
                                <h1><i class="fas fa-haykal fa-2x text-app-primary"></i></h1>
                                <p class="text-muted mt-4">
                                    {{ __('Sorry') }}, <br> {{ __('But seems that there are no questions for that page...') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import BaseFilters from './../Common/BaseFilters.vue';
import BaseSorts from './../Common/BaseFilters.vue';

export default {
    props: {
        startPoint: {
            type: String,
            required: true
        },
        method: {
            type: String,
            required: false
        }
    },

    components: {
        BaseFilters, BaseSorts
    },

    data () {
        return {
            questions: [],
            nextUrl: null,
            noQuestionsMessage: false,
            current_filter: {
                title: 'All questions',
                type: 'all',
            },
            filters: [
                {
                    title: 'All questions',
                    type: 'all',
                },
                {
                    title: 'Favorites',
                    type: 'favorites',
                },
                {
                    title: 'Without Answer',
                    type: 'need_answer',
                },
            ],
            current_sort: {
                title: 'Latest',
                type: 'latest',
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
                    title: 'Most Popular',
                    type: 'most_popular',
                },
                {
                    title: 'Most Viewed',
                    type: 'most_viewed',
                },
                {
                    title: 'Oldest',
                    type: 'oldest',
                },
                {
                    title: 'Trending',
                    type: 'trending',
                }
            ]
        }
    },

    computed: {
        isFeed () {
            return this.method == 'feed';
        }
    },

    created () {
        this.fetch(this.startPoint);
    },

    methods: {
        fetch (endpoint) {
            this.startUpdating();
            axios.get(endpoint).then(({data}) => {
                this.questions.push(...data.data);
                this.nextUrl = data.next_page_url;
                this.stopUpdating();
                this.noQuestionsMessage = (this.questions.length == 0);
            });
        },
        remove (index) {
            this.questions.splice(index, 1);
        },
        apply_filter_and_sort () {
            var url = this.startPoint;
            this.questions = [];
            if (this.current_filter.type != 'all') {
                url = this.addParameterToURL(url, 'only='+this.current_filter.type);
            }
            url = this.addParameterToURL(url, 'order_by='+this.current_sort.type);
            this.fetch(url);
        },
        update_filters (filter) {
            this.current_filter = filter;
            this.apply_filter_and_sort();
        },
        update_sorts (sort) {
            this.current_sort = sort;
            this.apply_filter_and_sort();
        }
    }
}
</script>
