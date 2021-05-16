<template>
    <form class="mr-auto d-inline" v-on:submit.prevent>
        <div class="has-search position-relative">
            <span class="fa fa-search form-control-feedback"></span>
            <input
                v-model="search"
                type="text"
                class="form-control bg-app-secondary border"
                :placeholder="__('Search anything...')"
                @keyup.down="onArrowDown"
                @keyup.up="onArrowUp"
                @keyup.enter.prevent="onEnter">
            <span
                v-if="hasResults"
                @click.prevent="cleanSearch"
                class="fa fa-times clean-search">
            </span>
            <div
                v-if="hasResults"
                class="results position-absolute w-100 bg-white border py-1 border-top-0">
                <a v-for="(result, i) in results" :href="result.path" class="d-block p-1"
                    :class="{ 'selected': i === arrowPosition }" :key="i">
                    <span class="badge badge-light" v-text="__(result.model_type)"></span>
                    <span v-if="result.title" v-text="result.title"></span>
                    <span v-if="result.name" v-text="result.name"></span>
                </a>
            </div>
        </div>
    </form>
</template>

<script type="text/javascript">
export default {
    data () {
        return {
            search: '',
            hasResults: false,
            results: [],
            arrowPosition: -1,
        }
    },

    watch: {
        search: function (value) {
            if (value && value.length > 0) {
                this.fetch('/search/'+value+'?all=1');
            } else {
                this.cleanSearch();
            }
        }
    },

    methods: {
        fetch (endpoint) {
            axios.post(endpoint).then(({data}) => {
                this.results = [];
                this.results.push(...data);
                this.hasResults = (this.results.length > 0);
            });
        },
        onArrowDown () {
            if (this.arrowPosition < (this.results.length - 1)) {
                this.arrowPosition = this.arrowPosition + 1;
            }
        },
        onArrowUp () {
            if (this.arrowPosition >= 0) {
                this.arrowPosition = this.arrowPosition - 1;
            }
        },
        onEnter () {
            if (this.arrowPosition < 0) {
                window.location.href = '/search/'+this.search;
            } else {
                window.location.href = this.results[this.arrowPosition].path;
            }
        },
        checkKeyInSearch (event) {
            if (event.keyCode === 27) {
                this.cleanSearch();
            }
        },
        cleanSearch() {
            this.search = '';
            this.hasResults = false;
            this.results = [];
            this.arrowPosition = 0;
        }
    },

    mounted() {
        document.addEventListener('keyup', this.checkKeyInSearch);
    },

    destroyed() {
        document.removeEventListener('keyup', this.checkKeyInSearch);
    }
}
</script>
