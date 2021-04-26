<template>
    <a-upload :file-list="fileList" name="file" :customRequest="dummyUpload">
        <a-button> <a-icon type="upload" /> Click to Upload </a-button>
    </a-upload>
</template>
<script>
export default {
    data() {
        return {
            fileList: []
        };
    },
    methods: {
        dummyUpload({ onSuccess, onError, file }) {
            onSuccess(true, file);
            this.fileList = [file];
            this.$emit("getUpload", this.fileList);
        }
    }
};
</script>
