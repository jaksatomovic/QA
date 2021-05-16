<template>
    <a @click.prevent="toggle" v-if="isAuth" href="#" class="btn btn-sm btn-outline-secondary no-underline p-0">
        <span class="d-inline-block py-1 px-2">
            <span v-if="isFavorited">
                <i class="fas fa-star text-warning"></i> <span class="d-none d-md-inline-block">{{ __('Favorited') }}</span>
            </span>
            <span v-else>
                <i class="fas fa-star"></i> <span class="d-none d-md-inline-block">{{ __('Add to favorites') }}</span>
            </span>
        </span>
        <span v-if="total > 0" class="d-inline-block py-1 px-2 border-left border-secondary">
            <span v-text="total"></span>
        </span>
    </a>
    <span v-else>
        <a @click.prevent="authAlert" href="#" class="btn btn-sm btn-outline-secondary no-underline p-0">
            <span class="d-inline-block py-1 px-2">
                <i class="fas fa-star text-muted"></i> <span v-text="total"></span>
            </span>
        </a>
        <span class="pl-2 text-muted">
            <a href="#" @click.prevent="showLoginModal">{{ __('Login') }}</a> {{ __('to vote or follow this question') }}
        </span>
    </span>
</template>

<script type="text/javascript">
export default {
    data () {
        return {
            id: this.$attrs.id,
            isFavorited: this.$attrs.is_favorited,
            total: this.$attrs.total || 0,
        }
    },

    methods: {
        toggle () {
            this.isFavorited ? this.destroy() : this.create();
        },
        destroy () {
            axios.delete('/questions/'+this.id+'/favorites')
            .then(response => {
                this.isFavorited = false;
                this.total = response.data.favorites;
            });
        },
        create () {
            axios.post('/questions/'+this.id+'/favorites')
            .then(response => {
                this.isFavorited = true;
                this.total = response.data.favorites;
            });
        },
        authAlert () {
            this.runToast(this.__('Hey anonymous,'), this.__('You need to login to add question into favorites!'), 'secondary');
        },
    }
}
</script>
