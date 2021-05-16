<template>
    <div>
        <div class="mb-3 d-flex justify-content-between">
            <div class="btn bg-white border" v-text="__('Reports')"></div>

            <div>
                <a href="#" @click.prevent="markAllAsSolved" class="btn btn-light bg-white border">
                    <small><i class="fa fa-check text-muted mr-1"></i></small> {{ __('Mark all as solved') }}
                </a>
            </div>
        </div>

        <div class="bg-white">
            <div class="card">
                <div class="card-body p-4 border-bottom">
                    <reports-chart
                        :title="__('Reports')"
                        type="reports"
                        :showAllBtn="false">
                    </reports-chart>
                </div>
            </div>
        </div>

        <div class="bg-white mt-3">
            <div class="card">
                <div class="admin-search has-search position-relative">
                    <form v-on:submit.prevent="search()">
                        <span class="fa fa-search form-control-feedback"></span>
                        <input v-model="query" :placeholder="__('Search in reports...')"
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
                    <report-item
                        v-for="(result, index) in results"
                        :report="result"
                        :key="result.id"
                        @deleted="remove(index)">
                    </report-item>

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
import ReportsChart from './../Common/BaseChart.vue';
import BasePagination from './../Common/BasePagination.vue';
import ReportItem from './ReportsItem.vue';

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
        ReportsChart, BasePagination, ReportItem
    },

    created () {
        this.fetch(this.startPoint);
    },

    methods: {
        markAllAsSolved () {
            axios.put('/admin/reports/solved').then(() => {
                window.events.$emit('reports-solved');
            });
        }
    }
}
</script>
