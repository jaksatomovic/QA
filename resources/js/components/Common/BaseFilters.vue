<template>
    <div class="dropdown">
        <button
            v-text="__(current_filter.title)"
            type="button"
            class="btn bg-white border dropdown-toggle"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">
        </button>
        <div class="dropdown-menu" :class="custom_class">
            <a
                v-for="filter in filters"
                @click.prevent="update_filter(filter)"
                href="#"
                class="dropdown-item pl-5">
                <i v-if="__(filter.type) == __(current_filter.type)" class="fa fa-check ml-n4 mr-1"></i>
                {{ __(filter.title) }}
            </a>
        </div>
    </div>
</template>

<script type="text/javascript">
export default {
    props: {
        filter_by: {
            type: Object,
            required: true
        },
        filters: {
            type: Array,
            required: true
        },
        custom_class: {
            type: String,
            required: false
        }
    },

    data () {
        return {
            current_filter: this.filter_by,
        }
    },

    methods: {
        update_filter (filter) {
            this.current_filter = filter;
            this.$emit('changed', this.current_filter);
        }
    }
}
</script>
