<template>
    <span :style="getPicture" class="avatar" v-text="getInitials"></span>
</template>

<script type="text/javascript">
export default {
    props: {
        user: {
            type: Object,
            required: false
        },
        src: {
            type: String,
            required: false
        },
    },

    computed: {
        hasPicture () {
            return this.user ? this.user.avatar != '/images/avatar.png' : true;
        },
        getPicture() {
            if (this.user) {
                return this.hasPicture ? 'background-image: url('+this.user.avatar+')' : null;
            } else {
                return 'background-image: url('+this.src+')';
            }
        },
        getInitials() {
            return this.hasPicture ? null : this.user.name.substring(0, 2);
        }
    }
}
</script>
