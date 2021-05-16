<template>
    <base-modal v-show="modalAsk" @close="hideModal">
        <template v-slot:header>
            <p class="mb-0" v-text="__('Add new question')"></p>
        </template>

        <template v-slot:body>
            <form v-if="isAuth">
                <div class="form-group">
                    <v-select
                        v-model="selectedTopics"
                        :placeholder="__('Type or choose topics')"
                        :options="topics"
                        label="title"
                        :multiple="true"
                        v-on:input="limitTopics">
                        <template slot="option" slot-scope="option">
                            {{ option.title }}
                        </template>
                    </v-select>
                    <label>
                        <small class="pl-1">
                            {{ __('Choose 1-5 topics for your question') }}.
                        </small>
                    </label>
                </div>

                <div class="form-group">
                    <input v-model="title" type="text" class="form-control" :placeholder="__('Question\'s title')">
                    <label>
                        <small class="pl-1">
                            {{ __('Your question\'s title. Try to make it simple and understandable for everyone') }}.
                        </small>
                    </label>
                </div>

                <div class="form-group">
                    <textarea v-model="body" class="form-control" :placeholder="__('Question\'s description')">
                    </textarea>
                    <label>
                        <small class="pl-1">
                            {{ __('Your question\'s description. Add here more details about your question') }}.
                        </small>
                    </label>
                </div>
            </form>
            <div v-else class="text-center">
                <h1><i class="fas fa-user-secret fa-2x text-app-primary"></i></h1>
                <p class="text-muted mt-4">
                    {{ __('Hey anonymous') }}, <br>
                    {{ __('You need to login to add new question!') }}
                </p>
                <a href="#" @click.prevent="showLoginModal">{{ __('Login') }}</a> {{ __('or') }}
                <a href="#" @click.prevent="showRegisterModal">{{ __('Register') }}</a>
            </div>
        </template>

        <template v-slot:footer>
            <button @click="hideModal" type="button" class="btn btn-light border">
                <span v-if="isAuth">{{ __('Close') }}</span>
                <span v-else>{{ __('OK') }}</span>
            </button>
            <button v-if="isAuth" @click="ask" :disabled="isInvalid" type="button" class="btn btn-action border px-5">
                <div v-if="isUpdating" class="spinner-border spinner-border-sm text-white">
                    <span class="sr-only" v-text="__('Loading...')"></span>
                </div>
                <span v-else>{{ __('Ask') }}</span>
            </button>
        </template>
    </base-modal>
</template>

<script type="text/javascript">
export default {
    data () {
        return {
            modalAsk: false,
            title: '',
            body: '',
            topic: '',
            topics: [],
            selectedTopics: [],
        }
    },

    computed: {
        isInvalid () {
            return (
                this.title.length < 5 || this.body.length < 5 ||
                this.title.length > 120 || this.body.length > 1000 ||
                this.selectedTopics.length < 1 || this.selectedTopics.length > 5
            );
        },
    },

    created () {
        window.events.$on('showAskModal', (topic = '') => {
            if (topic) {
                this.topic = topic;
                this.selectedTopics.push(topic);
            }
            this.showModal();
        });
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
                this.topics.push(...data);
            });
        },
        showModal () {
            this.modalAsk = true;
            if (this.topics.length == 0 && this.isAuth) {
               this.fetchTopics('/topics/all');
            }
        },
        hideModal () {
            this.modalAsk = false;
        },
        ask () {
            this.startUpdating();
            axios.post('/questions', {
                title: this.title,
                body: this.body,
                topics: this.selectedTopics.map(topic => {
                    return topic.id;
                }),
            }).then(res => {
                setTimeout(() => {
                    window.location.href = '/questions/'+res.data.slug;
                }, 500);
            }).catch(error => {
                var errors = "";
                if(error.response.data.errors.title) {
                    errors += error.response.data.errors.title+"<br>";
                }
                if(error.response.data.errors.body) {
                    errors += error.response.data.errors.body+"<br>";
                }
                if(error.response.data.errors.topics) {
                    errors += error.response.data.errors.topics;
                }
                this.runToast(this.__('Error!'), errors, 'danger');
                this.stopUpdating();
            });
        },
    }
}
</script>
