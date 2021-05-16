<template>
    <div v-if="!isDeleted">
        <div class="card-body p-3 border-bottom">
            <div v-if="!isApproved" class="alert alert-danger border mb-3" role="alert">
                <p class="mb-0">
                    <i class="fas fa-exclamation-circle text-danger"></i>
                    {{ __('This space is not approved yet. It can not be attached to question or displayed in spaces list.') }}
                </p>
            </div>

            <div class="d-flex">
                <div class="mr-2">
                    <base-avatar :src="image" class="avatar-md"></base-avatar>
                </div>
                <div>
                    <h5 class="mb-0">
                        <a :href="topic.path" class="text-dark" v-text="title"></a>
                        <a class="badge badge-light py-2 px-3 mr-1" v-text="__(type)"></a>
                    </h5>
                    <small class="d-block text-muted">
                        {{ topic.questions_count || 0 }} {{ __('questions') }}. {{ followers || 0 }} {{ __('followers') }}
                    </small>
                </div>
            </div>

            <p class="pt-2" v-text="body"></p>

            <div class="mt-3">

                <space-disapprove
                    v-if="isApproved && type == 'Space'"
                    :topic="topic"
                    @disapproved="markAsDisapproved">
                </space-disapprove>

                <space-approve
                    v-if="!isApproved && type == 'Space'"
                    :topic="topic"
                    @approved="markAsApproved">
                </space-approve>

                <topic-edit
                    :topic="topic"
                    @updated="update">
                </topic-edit>

                <topic-delete
                    :topic="topic"
                    @deleted="markAsDeleted">
                </topic-delete>
            </div>
        </div>
    </div>
</template>

<script type="text/javascript">
import SpaceApprove from './TopicsItemSpaceApprove.vue';
import SpaceDisapprove from './TopicsItemSpaceDisapprove.vue';
import TopicEdit from './TopicsItemTopicEdit.vue';
import TopicDelete from './TopicsItemTopicDelete.vue';

export default {
    props: {
        topic: {
            type: Object,
            required: true
        }
    },

    components: {
       SpaceApprove, SpaceDisapprove,
       TopicEdit, TopicDelete
    },

    data () {
        return {
            id: this.topic.id,
            title: this.topic.title,
            bodyHTML: this.topic.body_html,
            is_approved: this.topic.is_approved,
            is_deleted: 0,
            image: '/storage/'+this.topic.picture,
            followers: this.topic.followers_counted,
        }
    },

    computed: {
        body () {
            return this.bodyHTML.replace(/<\/?[^>]+(>|$)/g, "");
        },
        type () {
            return (this.topic.is_space == 1) ? 'Space' : 'Topic';
        },
        isApproved () {
            return this.is_approved == 1;
        },
        isDeleted () {
            return this.is_deleted == 1;
        },
    },

    methods: {
        markAsApproved () {
            this.is_approved = 1;
        },
        markAsDisapproved () {
            this.is_approved = 0;
        },
        markAsDeleted () {
            this.is_deleted = 1;
        },
        update (updated) {
            this.title = updated.title ? updated.title : this.title;
            this.image = updated.image ? updated.image : this.image;
            this.bodyHTML = updated.body ? updated.body : this.bodyHTML;
            this.is_approved = (updated.is_approved == 0) ? 0 : this.is_approved;
        }
    }
}
</script>
