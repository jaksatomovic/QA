<template>
    <nav class="pagination is-centered is-rounded" role="navigation" aria-label="pagination">
        <ul class="pagination">
            <li v-for="page in pages" class="page-item" :class="isCurrentPage(page) ? 'active' : ''">
                <a class="page-link" @click.prevent="changePage(page)" v-text="page"></a>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    props: [
        'pagination'
    ],

    methods: {
        isCurrentPage(page) {
            return this.pagination.current_page == page;
        },
        changePage(page) {
            if (page > this.pagination.last_page) {
                page = this.pagination.last_page;
            }
            this.pagination.current_page = page;
            this.$emit('paginate');
        }
    },

    computed: {
        pages() {
            let pages = [];
            let from = this.pagination.current_page - Math.floor(this.pagination.per_page / 2);
            if (from < 1) {
                from = 1;
            }
            let to = from + this.pagination.per_page - 1;
            if (to > this.pagination.last_page) {
                to = this.pagination.last_page;
            }
            while (from <= to) {
                pages.push(from);
                from++;
            }
            return pages;
        }
    }
}
</script>

<style scoped>
.pagination {
    margin-top: 40px;
}
</style>
