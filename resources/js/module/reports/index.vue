<template>
    <a-card title="Reports">
        <a-row :gutter="16">
            <a-col :span="7">
                <a-card title="Filters">
                    <filters @getParams="setFilters" />
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
                    <a-tabs
                        v-if="!showDetail"
                        default-active-key="1"
                        @change="changeTab"
                    >
                        <a-tab-pane key="1" tab="Cash">
                            <summary-report
                                :params="params"
                                v-if="!showDetail"
                                @selectType="selectType"
                            />
                        </a-tab-pane>
                        <a-tab-pane key="2" tab="Card" force-render>
                            <summary-report
                            
                                :params="{ pay_by_card: true }"
                                @selectType="selectType"
                            />
                        </a-tab-pane>
                    </a-tabs>

                    <detail :params="params" :type="selectedReport" v-else />
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
            showDetail: false,
            params: {}
        };
    },
    methods: {
        selectType(type) {
            this.selectedReport = type;
            this.showDetail = true;
        },
        handleDetailTab(show) {
            this.showDetail = show;
        },
        setFilters(filters) {
            this.params = filters;
        },
        changeTab() {}
    }
};
</script>
