<template>
    <div>
        <div class="mb-3 d-flex justify-content-between">
            <div class="btn bg-white border" v-text="__('Dashboard')"></div>
        </div>

        <div class="bg-white mb-3">
            <div class="card p-4">
                <div class="row">
                    <div class="col-6 text-center">
                        <i class="d-inline-block fas fa-flag fa-3x mr-2 text-muted"></i>
                        <div class="d-inline-block text-left">
                            <span v-if="newReports == 0" class="d-block" v-text="__('No new reports')"></span>
                            <span v-else class="d-block text-danger">
                                {{ newReports }} {{ __('unsolved reports') }}
                            </span>
                            <a href="/admin/reports" class="btn btn-outline-secondary btn-sm" v-text="__('View All')"></a>
                        </div>
                    </div>

                    <div class="col-6 text-center">
                        <i class="d-inline-block fas fa-star fa-3x mr-2 text-muted"></i>
                        <div class="d-inline-block text-left">
                            <span v-if="newSpaces == 0" class="d-block" v-text="__('No disapproved spaces')"></span>
                            <span v-else class="d-block text-danger">
                                {{ newSpaces }} {{ __('unapproved spaces') }}
                            </span>
                            <a href="/admin/topics" class="btn btn-outline-secondary btn-sm" v-text="__('View All')"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white">
            <div class="card">
                <div class="card-body p-4 border-bottom">
                    <questions-chart
                        :title="__('Questions')"
                        type="questions">
                    </questions-chart>
                </div>
                <div class="card-body p-4 border-bottom">
                    <answers-chart
                        :title="__('Answers')"
                        type="answers">
                    </answers-chart>
                </div>
                <div class="card-body p-4">
                    <users-chart
                        :title="__('Users')"
                        type="users">
                    </users-chart>
                </div>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import QuestionsChart from './Common/BaseChart.vue';
import AnswersChart from './Common/BaseChart.vue';
import UsersChart from './Common/BaseChart.vue';

export default {
    data () {
        return {
            newReports: 0,
            newSpaces: 0,
        }
    },

    components: {
        QuestionsChart, AnswersChart, UsersChart
    },

    created () {
        axios.get('/admin/reports-count-unsolved').then(({data}) => {
            this.newReports = (data);
        });

        axios.get('/admin/topics-count-unapproved').then(({data}) => {
            this.newSpaces = (data);
        });
    }
}
</script>
