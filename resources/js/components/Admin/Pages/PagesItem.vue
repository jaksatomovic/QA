<template>
    <div v-if="!isDeleted">
        <div class="card-body p-3 border-bottom">
            <div v-if="!isPublished" class="alert alert-danger border mb-3" role="alert">
                <p class="mb-0">
                    <i class="fas fa-exclamation-circle text-danger"></i>
                    {{ __('This page is not visible as it is not published.') }}
                </p>
            </div>

            <div class="d-flex">
                <h5 class="mb-0" v-text="title"></h5>
            </div>

            <div class="mt-3">
                <a :href="'/page/'+slug" class="btn btn-sm btn-outline-secondary no-underline" target="_blank">
                   <i class="fas fa-external-link-alt"></i> {{ __('View') }}
                </a>

                <page-edit
                    :page="page"
                    @edited="update">
                </page-edit>

                <page-delete
                    :page="page"
                    @deleted="markAsDeleted">
                </page-delete>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import PageEdit from './PagesItemPageEdit.vue';
import PageDelete from './PagesItemPageDelete.vue';

export default {
    props: {
        page: {
            type: Object,
            required: true
        }
    },

    components: {
       PageEdit, PageDelete
    },

    data () {
        return {
            id: this.page.id,
            title: this.page.title,
            slug: this.page.slug,
            is_published: this.page.is_published,
            is_deleted: 0,
        }
    },

    computed: {
        isPublished () {
            return this.is_published == 1;
        },
        isDeleted () {
            return this.is_deleted == 1;
        },
    },

    methods: {
        markAsDeleted () {
            this.is_deleted = 1;
        },
        update (updated) {
            this.title = updated.title;
            this.slug = updated.slug;
            this.is_published = updated.is_published;
        }
    }
}
</script>
