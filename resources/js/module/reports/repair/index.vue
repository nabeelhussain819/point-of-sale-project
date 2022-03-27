<template>
    <div class="row">
        <div><menus :activeKey="['repair']" /></div>
        <div class="row col-12">
            <div class="col-6">
                <a-form
                    :form="form"
                    :layout="formLayout"
                    class="print-repair-container"
                >
                    <a-form-item label="Duration">
                        <a-range-picker
                            v-decorator="[
                                'dateTime',
                                {
                                    initialValue: [
                                        getPastMoment(0),
                                        moment().set({ h: 23, m: 59 }),
                                    ],
                                },
                            ]"
                            :ranges="{
                                Today: [moment(), moment()],
                                Week: [getPastMoment(7), moment()],
                                Year: [getPastMoment(365), moment()],
                                Month: [getPastMoment(30), moment()],
                            }"
                            format="MM/DD/YYYY"
                            @change="dateRangeChange"
                        />
                    </a-form-item>
                </a-form>
            </div>
            <div class="col-6">
                <table class="table table-bordered">
                    <tr>
                        <td>Total Cost</td>
                        <td>${{ summary.total_cost }}</td>
                    </tr>
                    <tr>
                        <td>total Discount</td>
                        <td>${{ summary.total_discount }}</td>
                    </tr>
                    <tr>
                        <td>Total Amount Received In Duration</td>
                        <td>${{ summary.amount_received_in_duration }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="col-12 over">
            <a-table :pagination="false"  :dataSource="data" :columns="columns"></a-table>
        </div>
    </div>
</template>
<script>
import total from "../components/total";
import repair from "../../repair/index.vue";
import menus from "../components/menus";
import ReportsService from "../../../services/API/ReportsServices";

import moment from "moment";
const dateTimeFormat = "YYYY-MM-DDTHH:mm:ss";
const columns = [
    {
        title: "id",
        dataIndex: "id",
        key: "id",
    },
    {
        title: "status",
        dataIndex: "status",
        key: "status",
    },
    {
        title: "Total cost",
        dataIndex: "total_cost",
        key: "total_cost",
    },
    {
        title: "Advance Cost",
        dataIndex: "advance_cost",
        key: "advance_cost",
    },
    {
        title: "Amount received in Durration",
        dataIndex: "amount_received_in_duration",
        key: "amount_received_in_duration",
    },
    {
        title: "Discount ",
        dataIndex: "discounted_on",
        key: "discounted_on",
    },
];
export default {
    components: { total, repair, menus },
    props: {
        showFooter: {
            default: () => false,
            type: Boolean,
        },
    },
    data() {
        return {
            columns,
            data: [],
            filters: {},
            categories: [],
            departments: [],
            params: {},
            summary: {},
            formLayout: "vertical",
            form: this.$form.createForm(this, { name: "addRepair" }),
            showOrderModal: false,
            orderIds: [],
            fetchFinance: () => {},
        };
    },
    mounted() {
        this.params = {
            date_range: [
                this.getPastMoment(0).format(dateTimeFormat),
                moment().set({ h: 23, m: 59 }).format(dateTimeFormat),
            ],
            apply_on_update: true,
        };
        this.fetch();
    },

    methods: {
        fetch() {
            ReportsService.getRepairStats(this.params).then((response) => {
                this.summary = response;
                this.data = response.data;
            });
            this.fetchFinance(this.params);
        },
        moment,

        getPastMoment(days) {
            return moment().subtract(days, "days").set({ h: 0, m: 0 });
        },
        dateRangeChange(dates) {
            const params = this.params;
            params.date_range = [
                dates[0].format(dateTimeFormat),
                dates[1].format(dateTimeFormat),
            ];

            this.params = params;
            this.fetch(this.params);
        },

        handleSearch(value, column) {
            let filters = this.params;
            filters[column.key] = value[0];
            this.setFilters(filters);
        },
        setFilters(params) {
            this.params = params;
            this.fetch(this.params);
        },

        getFetch(postedFunction) {
            this.fetchFinance = postedFunction;
        },
    },
};
</script>
