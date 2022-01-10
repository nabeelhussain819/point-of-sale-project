<template>
    <div class="row ">
        <div><menus :activeKey="['inventory']" /></div>
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
                                        moment().set({ h: 23, m: 59 })
                                    ]
                                }
                            ]"
                            :ranges="{
                                Today: [moment(), moment()],
                                Week: [getPastMoment(7), moment()],
                                Year: [getPastMoment(365), moment()],
                                Month: [getPastMoment(30), moment()]
                            }"
                            format="MM/DD/YYYY"
                            @change="dateRangeChange"
                        />
                    </a-form-item>
                </a-form>
            </div>
        </div>
        <div class="col-12 over">
            <a-table
                :scroll="{ x: 900 }"
                :pagination="false"
                :columns="columns"
                :data-source="data"
            >
                <span
                    slot="name"
                    class="text-capitalize text-primary"
                    slot-scope="text, row"
                >
                    {{row.name}}
                </span>
                <span slot="inProduct" slot-scope="text, row">
                    {{ getInProperties(row) }}
                </span>
                <span slot="outProduct" slot-scope="text, row">
                    {{ getOutProperties(row) }}
                </span>
            </a-table>
        </div>
    </div>
</template>
<script>
import total from "../components/total";

import menus from "../components/menus";
import ReportsService from "../../../services/API/ReportsServices";

import moment from "moment";
import { isEmpty } from "../../../services/helpers";
const dateTimeFormat = "YYYY-MM-DDTHH:mm:ss";
export default {
    components: { total, menus },
    props: {
        showFooter: {
            default: () => false,
            type: Boolean
        }
    },
    data() {
        return {
            data: [],
            columns: [
                {
                    title: "Name",
                    key: "name",
                    scopedSlots: { customRender: "name" }
                },
                {
                    title: "Quantity",
                    dataIndex: "quantity",
                    key: "quantity"
                },
                {
                    title: "In",
                    key: "in",
                    scopedSlots: { customRender: "inProduct" }
                },
                {
                    title: "out",
                    key: "out",
                    scopedSlots: { customRender: "outProduct" }
                }
            ],
            filters: {},
            categories: [],
            departments: [],
            params: {},
            summary: {},
            formLayout: "vertical",
            form: this.$form.createForm(this, { name: "addRepair" }),
            showOrderModal: false,
            orderIds: [],
            fetchFinance: () => {}
        };
    },
    mounted() {
        this.params = {
            date_range: [
                this.getPastMoment(0).format(dateTimeFormat),
                moment()
                    .set({ h: 23, m: 59 })
                    .format(dateTimeFormat)
            ],
            apply_on_update: true
        };
        this.fetch();
    },

    methods: {
        fetch() {
            ReportsService.getInventoryStats(this.params).then(response => {
                console.log(response);
                this.data = response;
            });
        },
        moment,
        getPastMoment(days) {
            return moment()
                .subtract(days, "days")
                .set({ h: 0, m: 0 });
        },
        dateRangeChange(dates) {
            const params = this.params;
            params.date_range = [
                dates[0].format(dateTimeFormat),
                dates[1].format(dateTimeFormat)
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
        getName(getName) {
            return getName.name;
            // const response = JSON.parse(getName);
            // return response.name;
        },
        getInProperties(row) {
            let property = JSON.parse(row.properties);
            if (!isEmpty(property[0])) {
                return property[0].attributes.quantity;
            }
            return 0;
        },
        getOutProperties(row) {
            let property = JSON.parse(row.properties);
            if (!isEmpty(property)) {
                if (!isEmpty(property.at(-1).old))
                    return property.at(-1).old.quantity;
            }
            return 0;
        }
    }
};
</script>
