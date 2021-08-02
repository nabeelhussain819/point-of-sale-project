<template>
    <a-row>
        <a-col :span="12">
            <a-list
                :loading="loading"
                item-layout="horizontal"
                :data-source="records"
            >
                <a-list-item slot="renderItem" slot-scope="item">
                    <a-button
                        type="link"
                        @click="goto(item.name)"
                        class="col-12"
                    >
                        {{ item.name }}
                        <span class="float-right"> ${{ item.total }}</span>
                    </a-button>
                </a-list-item>
            </a-list>
        </a-col>
        <a-col :span="12">
            <a-skeleton :loading="!(label.length > 0)">
                <barChart :label="label" :data="data" /> </a-skeleton
        ></a-col>
    </a-row>
</template>
<script>
import moment from "moment";
import barChart from "./barChart";
import filters from "./filters";
import ReportsService from "../../services/API/ReportsServices";
export default {
    components: { filters, barChart },
    data() {
        return {
            loading: true,
            dateFormat: "YYYY/MM/DD",
            monthFormat: "YYYY/MM",
            records: [],
            showBar: false,
            label: [],
            data: []
        };
    },
    mounted() {
        this.fetch();
    },
    methods: {
        goto(type) {
            this.$emit("selectType", type);
            //  return (window.location.href = "/reports/" + name);
        },
        moment,
        onChange(dates, dateStrings) {
            console.log("From: ", dates[0], ", to: ", dates[1]);
            console.log("From: ", dateStrings[0], ", to: ", dateStrings[1]);
        },
        getPastMoment(days) {
            return moment().subtract(days, "days");
        },
        async fetch(params = {}) {
            this.loading = true;
            ReportsService.mainCategory(params)
                .then(records => {
                    let lables = [];
                    this.records = records;
                    this.records.map(m => {
                        lables.push(m.name);

                        this.data.push(m.total);
                        return m;
                    });

                    this.label = lables;
                })
                .finally(() => (this.loading = false));
        }
    }
};
</script>
