<template>
    <div class="custom-file btn-sm my-1">
        <input type="file" :name="name" accept="image/*"
            @change="onChange" class="custom-file-input">
        <label class="custom-file-label" v-text="text"></label>
    </div>
</template>

<script type="text/javascript">
export default {
    props: {
        name: {
            type: String,
            required: true
        },
        text: {
            type: String,
            required: true
        },
    },

    methods: {
        onChange (e) {
            if (! e.target.files.length) return;

            let file = e.target.files[0];

            let reader = new FileReader();

            reader.readAsDataURL(file);

            reader.onload = e => {
                let src = e.target.result
                this.$emit('loaded', { src, file });
                this.avatar = e.target.result;
            };
        },
    }
}
</script>
