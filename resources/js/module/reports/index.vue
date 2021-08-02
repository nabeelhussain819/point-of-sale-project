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
                    <a-button
                        type="primary"
                        slot="extra"
                        v-if="showDetail"
                        @click="handleDetailTab(false)"
                        >Back</a-button
                    >
                    <summary-report
                        v-if="!showDetail"
                        @selectType="selectType"
                    />
                    <detail :type="selectedReport" v-else />
                </a-card>
            </a-col>
        </a-row>
    </a-card>
</template>
<script>
import summaryReport from "./summary";
import detail from "./detail";
import filters from "./filters";
export default {
    components: { summaryReport, filters, detail },
    data() {
        return {
            selectedReport: null,
            showDetail: false
        };
    },
    methods: {
        selectType(type) {
            this.selectedReport = type;
            this.showDetail = true;
        },
        handleDetailTab(show) {
            this.showDetail = show;
        }
    }
};
</script>
