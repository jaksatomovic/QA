<template>
    <base-modal v-show="isVisible" @close="hideModal">
        <template v-slot:header>
            <p class="mb-0" v-text="__('Share')"></p>
        </template>

        <template v-slot:body>
            <div class="row share">
                <a v-for="service in shareLinks" @click.prevent="share(service.link+url)"
                    href="#" target="_blank" class="d-block no-decoration text-center col-4 p-2">
                    <i :class="service.icon" class="fa-2x" :style="'color: '+service.color"></i>
                    <small class="d-block text-dark" v-text="__(service.message)"></small>
                </a>
            </div>
        </template>

        <template v-slot:footer>
            <button @click="hideModal" type="button" class="btn btn-light border">
                {{ __('Close') }}
            </button>
        </template>
    </base-modal>
</template>

<script type="text/javascript">
export default {
    data () {
        return {
            isVisible: false,
            url: '',
            shareLinks: [
                {
                    message: 'Share on Facebook',
                    link: 'https://www.facebook.com/sharer.php?u=',
                    icon: 'fab fa-facebook',
                    color: '#3B5998',
                },
                {
                    message: 'Share on Twitter',
                    link: 'https://twitter.com/share?url=',
                    icon: 'fab fa-twitter',
                    color: '#00B6F1',
                },
                {
                    message: 'Share on LinkedIn',
                    link: 'https://www.linkedin.com/shareArticle?url=',
                    icon: 'fab fa-linkedin',
                    color: '#007BB6',
                },
                {
                    message: 'Share on Tumblr',
                    link: 'https://www.tumblr.com/share/link?url=',
                    icon: 'fab fa-tumblr',
                    color: '##34526f',
                },
                {
                    message: 'Share on Reddit',
                    link: 'https://reddit.com/submit?url=',
                    icon: 'fab fa-reddit',
                    color: '#ff4500',
                },

            ],
        }
    },

    created () {
        window.events.$on('showShareModal', (url) => {
            this.url = url,
            this.showModal();
        });
    },

    methods: {
        showModal () {
            this.isVisible = true;
        },
        hideModal () {
            this.isVisible = false;
        },
        share (shareUrl) {
            window.open(shareUrl, '', 'menubar=no, toolbar=no,resizable=yes,scrollbars=yes,height=450,width=600');
            return false;
        }
    }
}
</script>
