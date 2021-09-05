<template>
    <a-skeleton :paragraph="{ rows: 10 }" :loading="loading">
        <productsReport
            v-if="type === 'Sales'"
            :columns="productColumns"
            :data="data"
        />
        <productsReport
            v-if="type === 'Finances'"
            :columns="financesColumns"
            :data="data"
        />
    </a-skeleton>
</template>
<script>
import ReportsService from "../../../services/API/ReportsServices";
import productsReport from "./productsReport";
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
        key: "category",
        scopedSlots: {
            filterDropdown: "catgoryDropdown",
            filterIcon: "filterIcon"
        }
    },
    {
        title: "Department",
        dataIndex: "product.department.name",
        key: "department",
        scopedSlots: {
            filterDropdown: "departmentDropdown",
            filterIcon: "filterIcon"
        }
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
        key: "category"
    },
    {
        title: "Department",
        dataIndex: "product.department.name",
        key: "department"
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
export default {
    components: { productsReport },
    props: {
        type: {
            default: "",
            type: String,
            require: true
        }
    },
    data() {
        return {
            loading: true,
            data: [],
            productColumns,
            financesColumns
        };
    },
    mounted() {
        this.fetch(this.type);
    },
    methods: {
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
