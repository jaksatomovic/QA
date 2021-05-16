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

        <div class="card p-0">
            <topic-item
                v-for="(topic, index) in topics"
                :key="topic.id"
                :topic="topic"
                :is-extended-template="true">
            </topic-item>

            <div v-if="nextUrl && !isUpdating" class="text-center my-3">
                <button @click.prevent="fetch(nextUrl)" class="btn btn-sm btn-outline-secondary">
                    {{ __('View more') }} {{ __(type) }}
                </button>
            </div>

            <base-loading v-if="isUpdating"></base-loading>

            <div v-if="noTopicsMessage" class="text-center py-4">
                <h1><i class="fas fa-haykal fa-2x text-app-primary"></i></h1>
                <p class="text-muted mt-4">
                    {{ __('Sorry') }}, <br> {{ __('But seems that there are no results for that page...') }}
                </p>
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
        type: {
            type: String,
            required: true
        },
    },

    components: {
        BaseFilters, BaseSorts
    },

    data () {
        return {
            topics: [],
            nextUrl: null,
            noTopicsMessage: false,
            current_sort: {
                title: 'Most Followed',
                type: 'most_followed',
            },
            sorts: [
                {
                    title: 'A-Z',
                    type: 'title_asc',
                },
                {
                    title: 'Z-A',
                    type: 'title_desc',
                },
                {
                    title: 'Most Followed',
                    type: 'most_followed',
                },
            ],
            current_filter: {
                title: 'All '+this.type,
                type: 'all',
            },
            filters: [
                {
                    title: 'All '+this.type,
                    type: 'all',
                },
                {
                    title: 'Followed',
                    type: 'followed',
                },
                {
                    title: 'Owner',
                    type: 'owner',
                },
            ],
        }
    },

    created () {
        this.fetch(this.startPoint);
    },

    methods: {
        fetch (endPoint) {
            this.startUpdating();
            axios.get(endPoint).then(({data}) => {
                this.topics.push(...data.data);
                this.nextUrl = data.next_page_url;
                this.stopUpdating();
                this.noTopicsMessage = (this.topics.length == 0);
            });
        },
        apply_filter_and_sort () {
            var url = this.startPoint;
            this.topics = [];
            if (this.current_filter.type != 'all') {
                url = this.addParameterToURL(url, 'only='+this.current_filter.type);
            }
            url = this.addParameterToURL(url, 'order_by='+this.current_sort.type);
            this.fetch(url);
        },
        update_filters(filter) {
            this.current_filter = filter;
            this.apply_filter_and_sort();
        },
        update_sorts(sort) {
            this.current_sort = sort;
            this.apply_filter_and_sort();
        }
    }
}
</script>
