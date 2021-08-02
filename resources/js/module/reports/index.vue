<template>
    <a-card title="Reports">
        <a-row :gutter="16">
            <a-col :span="7">
                <a-card title="Filters">
                    <filters />
                </a-card>
            </a-col>
            <a-col :span="17">
                <a-card title="Results">
                    <summary-report @selectType="selectType" />
                </a-card>
            </a-col>
        </a-row>
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
