<template>
    <div>
        <a v-show="!has_response" href="#" @click.prevent="check"
            class="btn btn-action btn-sm">
            <div v-if="isUpdating" class="spinner-border spinner-border-sm text-white">
                <span class="sr-only" v-text="__('Loading...')"></span>
            </div>
            <span v-else>{{ __('Check for updates') }}</span>
        </a>
        <div v-show="has_response">
            <p>Latest version is: <span v-text="last_version"></span>. Visit <a href="https://xandr.co/" target="_blank">xandr.co</a> for latest version.</p>
        </div>
    </div>
</template>

<script type="text/javascript">
export default {
    data () {
        return {
            last_version: 'N/A',
            has_response: false,
        }
    },

    methods: {
        check () {
            this.startUpdating();
            axios.get('https://xandr.co/apps/qalar/version').then(({data}) => {
                this.has_response = true;
                this.last_version = data;
                this.stopUpdating();
            });
        }
    }
}
</script>
