<template>
    <div class="row ">
        <div><menus :activeKey="['sale']" /></div>
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
            <div class="col-6"><total :data="summary" /></div>
        </div>
        <div class="col-12 over">
            <a-table
                :scroll="{ x: 900 }"
                :pagination="false"
                :columns="columns"
                :data-source="data"
            >
                <a
                    slot="name"
                    class="text-capitalize text-primary"
                    slot-scope="text, row"
                >
                    <span @click="showOrders(row)" type="link">
                        {{ text }}
                    </span>
                </a>
                <a slot="total" class="text-capitalize" slot-scope="text"
                    >{{ text }}$</a
                >
                <div
                    slot="filterDropdown"
                    slot-scope="{ setSelectedKeys, selectedKeys, column }"
                    style="padding: 8px"
                >
                    <a-input
                        v-ant-ref="c => (searchInput = c)"
                        :placeholder="`Search ${column.dataIndex}`"
                        :value="selectedKeys[0]"
                        style="width: 188px; margin-bottom: 8px; display: block"
                        @change="
                            e =>
                                setSelectedKeys(
                                    e.target.value ? [e.target.value] : []
                                )
                        "
                        @pressEnter="() => handleSearch(selectedKeys, column)"
                    />
                    <a-button
                        type="primary"
                        icon="search"
                        size="small"
                        style="width: 90px; margin-right: 8px"
                        @click="() => handleSearch(selectedKeys, column)"
                    >
                        Search
                    </a-button>
                    <a-button
                        size="small"
                        style="width: 90px"
                        @click="() => handleReset(selectedKeys, column)"
                    >
                        Reset
                    </a-button>
                </div>

                <!-- Category search  -->
                <div
                    slot="catgoryDropdown"
                    slot-scope="{ setSelectedKeys, selectedKeys, column }"
                    style="padding: 8px;   min-width: 200px;"
                >
                    <a-select
                        style="width: 100%"
                        @change="value => handleSearch([value], column)"
                    >
                        <a-select-option
                            v-for="category in categories"
                            :key="category.id"
                        >
                            {{ category.name }}
                        </a-select-option>
                    </a-select>
                </div>
                <!-- Category search  -->
                <!-- Department search  -->
                <div
                    slot="departmentDropdown"
                    slot-scope="{ setSelectedKeys, selectedKeys, column }"
                    style="padding: 8px;    min-width: 200px;"
                >
                    <a-select
                        class="w-100"
                        style="width: 100%"
                        @change="value => handleSearch([value], column)"
                    >
                        <a-select-option
                            v-for="department in departments"
                            :key="department.id"
                        >
                            {{ department.name }}
                        </a-select-option>
                    </a-select>
                </div>
                <!-- Deparment search  -->

                <template slot="footer">
                    <div v-if="showFooter" class="text-right">
                        <strong>Total</strong> {{ data.total }}
                    </div>
                </template>
            </a-table>
        </div>

        <a-modal
            @cancel="handleModal(false)"
            :visible="showOrderModal"
            title="Order"
        >
            <ul>
                <li v-for="order in orderIds" :key="order.id">
                    <a-button type="link" @click="goto(order.order_id)"
                        >Order Number {{ order.order_id }}</a-button
                    >
                </li>
            </ul>

            <h2>Serial Number</h2>

            <ul>
                <li v-for="serial in serialNumbers" :key="serial.serial_number">
                    <span>{{ serial.serial_number }}</span>
                </li>
            </ul>
        </a-modal>
    </div>
</template>
<script>
import total from "../components/total";
import menus from "../components/menus";
import CategoryService from "../../../services/API/CategoryService";
import DepartmentService from "../../../services/API/DepartmentService";
import ReportsService from "../../../services/API/ReportsServices";
import OrderService from "../../../services/API/OrderServices";
import moment from "moment";
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
            serialNumbers: []
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
        this.fetch(this.params);
    },

    methods: {
        fetch(params) {
            ReportsService.getSalesStats(params).then(response => {
                this.data = response.data;
                this.summary = response.summary[0];
            });
        },
        moment,
        fetchCategoryService() {
            CategoryService.all().then(data => {
                this.categories = data;
            });
        },
        fetchDepartmentService() {
            DepartmentService.all().then(data => {
                this.departments = data;
            });
        },
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
        showOrders(row) {
            OrderService.getIds({ ...this.params, ...row })
                .then(response => {
                    this.orderIds = response;
                })
                .then(() => {
                    this.fetchSerials(row.product_id);
                    this.handleModal(true);
                });
        },
        fetchSerials(product_id, params = {}) {
            ReportsService.getReportSerials({
                product_id,
                ...this.params,
                ...params
            }).then(serialNumbers => {
                this.serialNumbers = serialNumbers;
            });
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
        handleModal(show) {
            this.showOrderModal = show;
        },
        goto(order) {
            window.location = "/sales?order_id=" + order;
        }
    }
};
</script>
