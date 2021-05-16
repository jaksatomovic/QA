<template>
    <div class="d-inline-block">

        <a @click.prevent="showEditModal" href="#"
            class="btn btn-sm btn-outline-secondary no-underline p-0">
            <span class="d-inline-block py-1 px-2">
                <i class="fas fa-edit"></i> {{ __('Edit Question') }}
            </span>
        </a>

        <base-modal v-show="modalEdit" @close="hideEditModal">
            <template v-slot:header>
                <p class="mb-0" v-text="__('Edit Question')"></p>
            </template>

            <template v-slot:body>
                <form @submit.prevent="edit">
                    <div class="form-group">
                        <v-select v-model="selectedTopics" :placeholder="__('Type or choose topics')" :options="modalTopics"
                            label="title" :multiple="true" v-on:input="limitTopics">
                            <template slot="option" slot-scope="option">
                                {{ option.title }}
                            </template>
                        </v-select>
                        <label for="topics">
                            <small class="pl-1" v-text="__('Choose 1-5 topics for your question')"></small>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="title" v-text="__('Question title')"></label>
                        <input type="text" class="form-control" v-model="title">
                    </div>

                    <div class="form-group">
                        <label for="body" v-text="__('Question body')"></label>
                        <textarea v-model="body" class="form-control"></textarea>
                    </div>
                </form>
            </template>

            <template v-slot:footer>
                <button @click="hideEditModal" type="button" class="btn btn-light border">
                    {{ __('Cancel') }}
                </button>
                <button @click="edit" :disabled="isInvalid" type="button" class="btn btn-action border">
                    <div v-if="isUpdating" class="spinner-border spinner-border-sm text-white ml-2 mr-2">
                        <span class="sr-only" v-text="__('Loading...')"></span>
                    </div>
                    <span v-else v-text="__('Save')"></span>
                </button>
            </template>
        </base-modal>
    </div>
</template>

<script type="text/javascript">
export default {
    props: {
        question: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            modalEdit: false,
            id: this.question.id,
            slug: this.question.path,
            title: this.question.title,
            topics: this.question.topics,
            body: this.question.body,
            bodyHTML: this.question.body_html,
            beforeEditCache: {},
            modalTopics: [],
            selectedTopics: this.question.topics,
        }
    },

    computed: {
        isInvalid () {
            return (
                this.title.length < 5 || this.body.length < 5 ||
                this.title.length > 120 || this.body.length > 1000 ||
                this.selectedTopics.length < 1 || this.selectedTopics.length > 5
            );
        }
    },

    methods: {
        limitTopics(e) {
            if(e.length > 5) {
                e.pop();
            }
        },
        fetchTopics (endPoint) {
            axios.get(endPoint)
            .then(({data}) => {
                this.modalTopics.push(...data);
            });
        },
        showEditModal () {
            this.beforeEditCache = {
                title: this.title,
                body: this.body,
            }
            if (this.modalTopics.length == 0) {
               this.fetchTopics('/topics/all');
            }
            this.modalEdit = true;
        },
        hideEditModal () {
            this.modalEdit = false;
            this.title = this.beforeEditCache.title;
            this.body = this.beforeEditCache.body;
        },
        edit () {
            this.startUpdating();
            axios.put('/admin/questions/'+this.id, {
                title: this.title,
                body: this.body,
                topics: this.selectedTopics.map(topic => {
                    return topic.id;
                }),
            }).then(res => {
                this.runToast(this.__('Success!'), res.data.message, 'success');
                this.$emit('updated', {
                    title: this.title,
                    slug: '/questions/'+res.data.slug,
                    topics: this.selectedTopics,
                    bodyHTML: res.data.body_html,
                });
                this.modalEdit = false;
            }).catch(error => {
                this.runToast(this.__('Error!'), error.response.data.message, 'danger');
            }).then(() => {
                this.stopUpdating();
            });
        }
    }
}
</script>
