<template>
    <div class="d-flex flex-column text-center mr-3 mr-lg-4 votes">
        <a @click.prevent="voteUp" href="#">
            <i class="fas fa-caret-up fa-2x text-muted"></i>
        </a>

        <h1 class="text-muted mb-0" v-text="count"></h1>
        <small class="text-muted">{{ __('votes') }}</small>

        <a @click.prevent="voteDown" href="#">
            <i class="fas fa-caret-down fa-2x text-muted"></i>
        </a>
    </div>
</template>

<script type="text/javascript">
export default {
    props: {
        type: {
            type: String,
            required: true
        },
        model: {
            type: Object,
            required: true
        }
    },

    data () {
        return {
            count: this.model.votes_counted || 0,
            id: this.model.id,
        }
    },

    methods: {
        voteUp () {
            this._vote(1);
        },
        voteDown () {
            this._vote(-1);
        },
        _vote (vote) {
            if(this.isAuth) {
                axios.post('/'+this.type+'s/'+this.id+'/vote', {
                    vote: vote
                }).then(response => {
                    this.runToast(this.__('Success!'), response.data.message, 'success');
                    this.count = response.data.votesCounted;
                }).catch(error => {
                    console.log(error);
                    this.runToast(this.__('Error!'), error.response.data.message, 'danger');
                });
            } else {
                this.runToast(this.__('Hey anonymous'), this.__('You need to login to vote questions!'), 'secondary');
            }
        }
    }
}
</script>
