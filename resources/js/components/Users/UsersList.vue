<template>
    <div>
        <div class="mb-3 d-flex justify-content-between">
            <div>
                <span></span>
            </div>

            <base-sorts
                :filter_by="current_sort"
                :filters="sorts"
                custom_class="dropdown-menu-right"
                @changed="update_sorts">
            </base-sorts>
        </div>

        <div class="card p-0">
            <user-item
                v-for="(user, index) in users"
                :key="user.id"
                :user="user"
                :is-extended-template="true">
            </user-item>

            <div v-if="nextUrl && !isUpdating" class="text-center my-3">
                <button @click.prevent="fetch(nextUrl)" class="btn btn-sm btn-outline-secondary">
                    {{ __('View more users') }}
                </button>
            </div>

            <base-loading v-if="isUpdating"></base-loading>

            <div v-if="noUsersMessage" class="text-center py-4">
                <h1><i class="fas fa-haykal fa-2x text-app-primary"></i></h1>
                <p class="text-muted mt-4">
                    {{ __('Sorry') }}, <br> {{ __('But seems that there are no users for that page...') }}
                </p>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import BaseSorts from './../Common/BaseFilters.vue';

export default {
    props: {
        startPoint: {
            type: String,
            required: true
        },
    },

    components: {
        BaseSorts
    },

    data () {
        return {
            users: [],
            nextUrl: null,
            noUsersMessage: false,
            current_sort: {
                title: 'Points',
                type: 'points',
            },
            sorts: [
                {
                    title: 'Points',
                    type: 'points',
                },
                {
                    title: 'A-Z',
                    type: 'name_asc',
                },
                {
                    title: 'Z-A',
                    type: 'name_desc',
                },
                {
                    title: 'Newest',
                    type: 'latest',
                },
                {
                    title: 'Oldest',
                    type: 'oldest',
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
            axios.get(endPoint)
            .then(({data}) => {
                this.users.push(...data.data);
                this.nextUrl = data.next_page_url;
                this.stopUpdating();
                this.noUsersMessage = (this.users.length == 0);
            });
        },
        apply_filter_and_sort () {
            var url = this.startPoint;
            this.users = [];
            url = this.addParameterToURL(url, 'order_by='+this.current_sort.type);
            this.fetch(url);
        },
        update_sorts(sort) {
            this.current_sort = sort;
            this.apply_filter_and_sort();
        }
    }
}
</script>
