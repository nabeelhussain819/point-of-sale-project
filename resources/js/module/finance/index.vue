<template>
    <a-card title="Finance" :bordered="false">
        <div slot="extra"><add @close="refresh" /></div>
        <list @getFetch="getFetch" />
    </a-card>
</template>

<script>
import list from "./list";
import add from "./add";
export default {
    props: { params: { type: Object, required: false, default: () => ({}) } },
    components: { list, add },
    data() {
        return {
            fetch: () => {}
        };
    },
    mounted() {
        this.$emit("getFetch", this.fetch);
    },
    methods: {
        refresh() {
            this.fetch(this.params);
        },
        getFetch(postedFunction) {
            this.fetch = postedFunction;
        }
    }
};
</script>
