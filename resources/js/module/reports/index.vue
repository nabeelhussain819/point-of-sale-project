<template>
    <a-card title="Reports">
        <filters />
        <a-divider></a-divider>
        <summary-report @selectType="selectType" />
    </a-card>
</template>
<script>
import summaryReport from "./summary";
import filters from "./filters";
export default {
    components: { summaryReport, filters },
    data() {
        return {
            selectedReport: null,
            showDetail: false
        };
    },
    methods: {
        selectType(type) {
            console.log(type);
        }
    }
};
</script>
