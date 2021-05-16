<template>
    <div class="mb-2">
        <img :src="avatar" class="img-thumbnail rounded">
        <form v-if="canUpdate" method="POST" enctype="multipart/form-data">
            <image-upload
                name="avatar"
                :text="__('Change avatar')"
                @loaded="onLoad">
            </image-upload>
        </form>
    </div>
</template>

<script type="text/javascript">
import ImageUpload from './../Common/ImageUpload.vue';

export default {
    props: {
        user: {
            type: Object,
            required: true
        },
    },

    components: {
        ImageUpload
    },

    data () {
        return {
            avatar: this.user.avatar,
        }
    },

    computed: {
        canUpdate() {
            return (this.authUser) ? this.authUser.id == this.user.id : false;
        },
    },

    methods: {
        onLoad(avatar) {
            this.upload(avatar);
        },
        upload (avatar) {
            let data = new FormData();

            data.append('avatar', avatar.file);

            axios.post('/users/id'+this.user.id+'/avatar', data)
            .then(res => {
                this.avatar = avatar.src;
                this.runToast(this.__('Success!'), __('Avatar updated!'), 'success');
            }).catch(error => {
                this.runToast(this.__('Error!'), error.response.data.errors.avatar[0], 'danger');
            });
        }
    }
}
</script>
