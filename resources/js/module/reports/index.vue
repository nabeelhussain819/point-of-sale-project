<template>
    <div>
        <filters />

        <a-row>
            <a-col :span="12">
                <a-card
                    @click=""
                    hoverable
                    v-for="record in records"
                    :key="record.name"
                    :title="record.name"
                >
                    <span>{{ record.total }}$</span>
                </a-card>
            </a-col>
            <a-col :span="12">
                <barChart v-if="label.length > 0" :label="label" :data="data"
            /></a-col>
        </a-row>
    </div>
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
        moment,
        onChange(dates, dateStrings) {
            console.log("From: ", dates[0], ", to: ", dates[1]);
            console.log("From: ", dateStrings[0], ", to: ", dateStrings[1]);
        },
        getPastMoment(days) {
            return moment().subtract(days, "days");
        },
        async fetch(params = {}) {
            ReportsService.mainCategory(params)
                .then(records => {
                    let lables = [];
                    this.records = records;
                    this.records.map(m => {
                        lables.push(m.name);

                        this.data.push(m.total);
                        return m;
                    });
                    console.log("parent", lables);
                    this.label = lables;
                })
                .then(() => {});
        }
    }
};
</script>
