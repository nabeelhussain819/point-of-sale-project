<template>
    <a-skeleton :paragraph="{ rows: 10 }" :loading="loading">
        <strong> {{ type }} Report</strong>
        <productsReport
            v-if="type === 'Sales'"
            :columns="productColumns"
            :data="data"
            @fetchSerial="fetchSerial"
            @fetch="eventSearch"
            :showFooter="true"
        />
        <productsReport
            v-if="type === 'Finances'"
            :columns="financesColumns"
            @fetchSerial="fetchSerial"
            :data="data"
            @fetch="eventSearch"
        />
        <productsReport
            v-if="type === 'Repair'"
            @fetchSerial="fetchSerial"
            :columns="repairColumns"
            :data="data"
            @fetch="eventSearch"
        />
        <productsReport
            v-if="type === 'Refunds'"
            :columns="refundColumns"
            @fetchSerial="fetchSerial"
            :data="data"
            @fetch="eventSearch"
        />
        <a-modal
            @cancel="displaySerialModal(false)"
            :visible="showserialmodal"
            title=""
        >
            <serials :data="serialNumbers" />
        </a-modal>
    </a-skeleton>
</template>
<script>
import ReportsService from "../../../services/API/ReportsServices";

import productsReport from "./productsReport";
import { EVENT_REPORT_FILTERS } from "../../../services/constants";
import serials from "./serials";
const productColumns = [
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
        },
        width: 200
    },
    {
        title: "Department",
        dataIndex: "product.department.name",
        key: "department_id",
        scopedSlots: {
            filterDropdown: "departmentDropdown",
            filterIcon: "filterIcon"
        },
        width: 200
    },
    {
        title: "Total",
        dataIndex: "total",
        key: "total",
        scopedSlots: { customRender: "total" }
    }
];
const financesColumns = [
    {
        title: "Name",
        dataIndex: "product.name",
        key: "name",
        scopedSlots: { customRender: "name" }
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
    },
    {
        title: "Quantity",
        dataIndex: "quantity",
        key: "quantity"
    },
    {
        title: "Advance",
        dataIndex: "advance",
        key: "advance",
        scopedSlots: { customRender: "total" }
    },
    {
        title: "Payable",
        dataIndex: "payable",
        key: "payable",
        scopedSlots: { customRender: "total" }
    },
    {
        title: "Total",
        dataIndex: "total",
        key: "total",
        scopedSlots: { customRender: "total" }
    }
];
const refundColumns = [
    {
        title: "Name",
        dataIndex: "product.name",
        key: "name",
        scopedSlots: { customRender: "name" }
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
    },
    {
        title: "Quantity",
        dataIndex: "quantity",
        key: "quantity"
    },
    {
        title: "Refund Cost",
        dataIndex: "total",
        key: "total",
        scopedSlots: { customRender: "total" }
    }
];
const repairColumns = [
    {
        title: "Name",
        dataIndex: "name",
        key: "name",
        scopedSlots: { customRender: "name" }
    },
    // {
    //     title: "Total",
    //     dataIndex: "total",
    //     key: "total",
    //     scopedSlots: { customRender: "total" }
    // },
    // {
    //     title: "Advance",
    //     dataIndex: "advance",
    //     key: "advance",
    //     scopedSlots: { customRender: "total" }
    // },
    {
        title: "Today received",
        dataIndex: "received_amount",
        key: "received_amount",
        scopedSlots: { customRender: "total" }
    },
    {
        title: "Card",
        dataIndex: "card",
        key: "card",
        scopedSlots: { customRender: "total" }
    },
    {
        title: "Cash",
        dataIndex: "cash",
        key: "cash",
        scopedSlots: { customRender: "total" }
    }
];
export default {
    components: { productsReport, serials },
    props: {
        type: {
            default: "",
            type: String,
            require: true
        },
        params: { default: {}, type: Object, require: false }
    },
    data() {
        return {
            showserialmodal: false,
            loading: true,
            data: [],
            productColumns,
            financesColumns,
            repairColumns,
            refundColumns,
            serialNumbers: []
        };
    },
    mounted() {
        this.fetch(this.type, this.params);
        let fetch = this.eventSearch;
        this.$eventBus.$on(EVENT_REPORT_FILTERS, function(filters) {
            fetch(filters);
        });
    },

    methods: {
        fetchSerial(productDetail) {
            this.displaySerialModal(true);
            this.fetchSerials(productDetail.product_id, {
                is_sold: true,
                apply_on_update: true
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
        displaySerialModal(show) {
            this.showserialmodal = show;
        },
        eventSearch(filters) {
            this.fetch(this.type, { ...this.params, ...filters });
        },
        fetch(type, params = {}) {
            this.loading = true;

            ReportsService.detail(type, params)
                .then(data => {
                    this.data = data;
                })
                .finally(() => (this.loading = false));
        }
    }
};
</script>
