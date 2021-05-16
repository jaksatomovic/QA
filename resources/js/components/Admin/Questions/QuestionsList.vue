<template>
    <div>
        <div class="mb-3 d-flex justify-content-between">
            <div class="btn bg-white border" v-text="__('Questions')"></div>
        </div>

        <div class="bg-white">
            <div class="card">
                <div class="card-body p-4 border-bottom">
                    <questions-chart
                        :title="__('Questions')"
                        :showAllBtn="false"
                        type="questions">
                    </questions-chart>
                </div>
            </div>
        </div>

        <div class="bg-white mt-3">
            <div class="card">
                <div class="admin-search has-search position-relative">
                    <form v-on:submit.prevent="search()">
                        <span class="fa fa-search form-control-feedback"></span>
                        <input v-model="query" :placeholder="__('Search in questions...')"
                            type="text" class="search-input py-2 border-0">
                        <button type="submit" class="btn btn-secondary btn-sm btn-search" @click="search()">
                            {{ __('Ok') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-white mt-3">
            <div class="card">
                <div class="card-body p-0">
                    <question-item
                        v-for="(question, index) in results"
                        :key="question.id"
                        :question="question">
                    </question-item

                    <base-loading v-if="isUpdating"></base-loading>

                    <div v-if="noResultsMessage" class="text-center py-4">
                        <h1><i class="fas fa-ellipsis-h fa-2x text-app-primary"></i></h1>
                        <p class="text-muted mt-4" v-text="__('Nothing here...')"></p>
                    </div>

                    <div class="px-4">
                        <base-pagination
                            v-if="pagination.last_page > 1"
                            :pagination="pagination"
                            @paginate="paginate()">
                        </base-pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import AdminCommon from './../Common/AdminCommon.js';
import QuestionsChart from './../Common/BaseChart.vue';
import BasePagination from './../Common/BasePagination.vue';
import QuestionItem from './QuestionsItem.vue';

export default {
    props: {
        startPoint: {
            type: String,
            required: true
        }
    },

    mixins: [
        AdminCommon
    ],

    components: {
        QuestionsChart, BasePagination, QuestionItem
    },

    created () {
        this.fetch(this.startPoint);
    }
}
</script>
