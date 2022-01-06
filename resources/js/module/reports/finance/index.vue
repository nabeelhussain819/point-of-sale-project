<template>
    <div class="row ">
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
            <finance :params="params" @getFetch="getFetch" />
        </div>
    </div>
</template>
<script>
import total from "../components/total";
import finance from "../../finance/index";

import ReportsService from "../../../services/API/ReportsServices";

import moment from "moment";
const dateTimeFormat = "YYYY-MM-DDTHH:mm:ss";
export default {
    components: { total, finance },
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
                    dataIndex: "product.name",
                    key: "name",
                    scopedSlots: { customRender: "name" }
                },
                {
                    title: "Quantity",
                    dataIndex: "quantity",
                    key: "quantity"
                },
                {
                    title: "Category",
                    dataIndex: "product.category.name",
                    key: "category_id",
                    scopedSlots: {
                        filterDropdown: "catgoryDropdown",
                        filterIcon: "filterIcon"
                    }
                },
                {
                    title: "Department",
                    dataIndex: "product.department.name",
                    key: "department_id",
                    scopedSlots: {
                        filterDropdown: "departmentDropdown",
                        filterIcon: "filterIcon"
                    }
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
        this.fetchCategoryService();
        this.fetchDepartmentService();
        this.params = {
            date_range: [
                this.getPastMoment(0).format(dateTimeFormat),
                moment()
                    .set({ h: 23, m: 59 })
                    .format(dateTimeFormat)
            ]
        };
        this.fetch();
    },

    methods: {
        fetch() {
            this.fetchFinance(this.params);
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

        getFetch(postedFunction) {
            this.fetchFinance = postedFunction;
        }
    }
};
</script>
