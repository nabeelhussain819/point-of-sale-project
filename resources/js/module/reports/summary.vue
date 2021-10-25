<template>
    <a-row>
        <a-col :span="24">
            <!-- <a-list
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
            </a-list> -->
            <a-table
                :pagination="false"
                :columns="columns"
                :data-source="records"
            >
                <span slot="name" slot-scope="text, row">
                    <a-button
                        v-if="row.link !== false"
                        @click="goto(row.name)"
                        type="link"
                        >{{ text }}</a-button
                    >
                    <strong v-else>{{ text }}</strong>
                </span>

                <span slot="total" slot-scope="text, row">
                    <a-button
                        v-if="row.link !== false"
                        @click="goto(row.name)"
                        type="link"
                        >${{ text }}</a-button
                    >
                    <strong v-else>${{ text }}</strong>
                </span>
            </a-table>
        </a-col>
        <!-- <a-col :span="12">
            <a-skeleton :loading="!(label.length > 0)">
                <barChart :label="label" :data="data" /> </a-skeleton
        ></a-col> -->
    </a-row>
</template>
<script>
import moment from "moment";
import barChart from "./barChart";
import filters from "./filters";
import ReportsService from "../../services/API/ReportsServices";
import { EVENT_REPORT_FILTERS } from "../../services/constants";
const columns = [
    {
        title: "Name",
        dataIndex: "name",
        key: "name",
        scopedSlots: { customRender: "name" }
    },
    {
        title: "Total",
        dataIndex: "total",
        key: "total",
        dataIndex: "total",
        scopedSlots: { customRender: "total" }
    }
];
export default {
    props: {
        params: {
            default: () => {},
            type: Object
        }
    },
    components: { filters, barChart },
    data() {
        return {
            columns,
            loading: true,
            records: [],
            showBar: false,
            label: [],
            data: []
        };
    },
    mounted() {
        let fetch = this.fetch;
        fetch(this.params);
        this.$eventBus.$on(EVENT_REPORT_FILTERS, function(filters) {
            fetch(filters);
        });
    },
    methods: {
        goto(type) {
            this.$emit("selectType", type);
            //  return (window.location.href = "/reports/" + name);
        },
        moment,
        onChange(dates, dateStrings) {
            // console.log("From: ", dates[0], ", to: ", dates[1]);
            // console.log("From: ", dateStrings[0], ", to: ", dateStrings[1]);
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
