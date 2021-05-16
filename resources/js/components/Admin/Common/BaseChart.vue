<template>
    <div>
        <line-chart
            v-if="dataLoaded"
            :chartdata="datacollection"
            :options="chartOptions"
            class="chart">
        </line-chart>

        <div class="d-flex justify-content-between mt-2">
            <div>
                <h4 class="mb-0" v-text="title"></h4>
                <div>
                    <small class="text-muted">
                        {{ __('Today') }}: <b class="text-dark" v-text="results.today"></b>.
                    </small>
                    <small class="text-muted">
                        {{ __('Yesterday') }}: <b class="text-dark" v-text="results.yesterday"></b>.
                    </small>
                    <small class="text-muted">
                        {{ __('Last 30 days') }}: <b class="text-dark" v-text="results.month"></b>.
                    </small>
                    <small class="text-muted">
                        {{ __('Total') }}: <b class="text-dark" v-text="results.total"></b>.
                    </small>
                </div>
            </div>
            <div v-if="showAllBtn">
                <a :href="'/admin/'+type" class="btn btn-action btn-sm mt-2" v-text="__('View All')"></a>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import LineChart from './LineChart.js'

export default {
    props: {
        type: {
            type: String,
            required: true
        },
        title: {
            type: String,
            required: true
        },
        showAllBtn: {
            type: Boolean,
            required: false,
            default: true
        }
    },

    data () {
        return {
            dataLoaded: false,
            datacollection: null,
            labels: [],
            resultsCounted: [],
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                }
            },
            results: {
                today: 0,
                yesterday: 0,
                month: 0,
                total: 0,
            }
        }
    },

    components: {
        LineChart
    },

    mounted () {
        this.fillData();
        this.countData();
    },

    methods: {
        fillData () {
            axios.get('/admin/'+this.type+'-counted').then(({data}) => {
                this.resultsCounted = data.map(data => data.total)
                this.labels = data.map(data => data.date)
                this.datacollection = {
                    labels: this.labels,
                    datasets: [
                        {
                            label: this.title,
                            backgroundColor: '#dfdfdf',
                            borderColor: '#6c757d',
                            borderWidth: 1,
                            data: this.resultsCounted
                        }
                    ]
                };
                this.dataLoaded = true;
                this.stopUpdating();
            });
        },
        formatDate (date) {
            var d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;

            return [year, month, day].join('-');
        },
        countData () {
            var today = new Date();
            var yesterday = new Date();
            var tomorrow = new Date();
            var monthAgo = new Date();
            yesterday.setDate(today.getDate() - 1);
            tomorrow.setDate(today.getDate() + 1);
            monthAgo.setDate(today.getDate() - 30);

            axios.get('/admin/'+this.type+'-count?from='+this.formatDate(today)+'&to='+this.formatDate(tomorrow))
            .then(data => {
                this.results.today = data.data;
            });

            axios.get('/admin/'+this.type+'-count?from='+this.formatDate(yesterday)+'&to='+this.formatDate(today))
            .then(data => {
                this.results.yesterday = data.data;
            });

            axios.get('/admin/'+this.type+'-count?from='+this.formatDate(monthAgo)+'&to='+this.formatDate(tomorrow))
            .then(data => {
                this.results.month = data.data;
            });

            axios.get('/admin/'+this.type+'-count?from='+this.formatDate('2019-01-01')+'&to='+this.formatDate(tomorrow))
            .then(data => {
                this.results.total = data.data;
            });
        }
    }
}
</script>

<style scoped>
.chart {
    position: relative;
    max-width: 100%;
    max-height: 150px;
}
</style>
