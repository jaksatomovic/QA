export default {
    data () {
        return {
            results: [],
            noResultsMessage: false,
            query: '',
            pagination: {
                'current_page': 1
            }
        }
    },

    methods: {
        fetch (endPoint) {
            this.results = [];
            this.startUpdating();
            axios.get(endPoint).then(({data}) => {
                this.results = data.data.data;
                this.pagination = data.pagination;
                this.noResultsMessage = (this.results.length == 0);
                this.stopUpdating();
            });
        },
        paginate () {
            var endPoint = this.startPoint+ '?page=' + this.pagination.current_page
            if (this.query.length > 1) {
                endPoint += '&has=' + this.query;
            }
            this.fetch(endPoint);
        },
        search () {
            this.results = [];
            this.fetch(this.startPoint+ '?has=' + this.query);
        }
    }
}
