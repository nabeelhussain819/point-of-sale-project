<template>
    <span>
        <list :showAdd="showAdd" @getFetch="getFetch" />
    </span>
</template>

<script>
import list from "./list";
export default {
    props: { showAdd: { type: Boolean, required: false, default: true } },
    components: { list },
    data() {
        return {};
    },
    methods: {
        getFetch(postedFunction) {
            this.$emit("getFetch", postedFunction);
        }
    },
    mounted() {}
};
</script>
