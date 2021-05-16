<template>
    <div>
        <div class="mb-3 d-flex justify-content-between">
            <div class="bg-white border p-2">
                <span>{{ __('Results for:') }} <b>{{ query }}</b></span>
            </div>
        </div>

        <div v-if="hasResults" class="bg-white">
            <div class="card">
                <div class="card-body p-0">
                    <div v-for="(result, i) in results" :key="i" class="media border-bottom">
                        <div class="media-body p-2">
                            <span class="badge badge-light" v-text="__(result.model_type)"></span>

                            <div class="pl-1">
                                <a :href="result.path">
                                    <p class="mb-0">
                                        <b v-if="result.title" v-text="result.title"></b>
                                        <b v-if="result.name" v-text="result.name"></b>
                                    </p>
                                </a>
                                <small v-if="result.credentials" v-text="result.credentials" class="d-block"></small>
                                <small v-if="result.body_text" v-text="result.body_text" class="d-block"></small>
                            </div>
                        </div>
                    </div>

                    <div v-if="nextUrl && !isUpdating" class="text-center my-3">
                        <button @click.prevent="fetch(nextUrl)" class="btn btn-sm btn-outline-secondary">
                            {{ __('View more results') }}
                        </button>
                    </div>

                    <base-loading v-if="isUpdating"></base-loading>

                    <div v-if="noResultsMessage" class="text-center py-4">
                        <h1><i class="fas fa-haykal fa-2x text-app-primary"></i></h1>
                        <p class="text-muted mt-4" v-text="__('There are not more results...')"></p>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="bg-white p-3 border">
            <p class="m-0" v-text="__('No results yet...')"></p>
        </div>
    </div>
</template>

<script type="text/javascript">
export default {
    props: {
        query: {
            type: String,
            required: false
        },
    },

    data () {
        return {
            results: [],
            nextUrl: null,
            hasResults: false,
            noResultsMessage: false,
        }
    },

    created () {
        if (this.query) {
            this.fetch('/search/'+this.query+'?all=1&limit=20');
        }
    },

    methods: {
        fetch (endPoint) {
            this.startUpdating();
            axios.post(endPoint).then(({data}) => {
                this.results.push(...data);
                this.hasResults = (this.results.length > 0);
                this.stopUpdating();
            });
        },
    }
}
</script>
