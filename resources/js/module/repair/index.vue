<template>
    <div>
        <a-skeleton :loading="loading">
            <a-table
            class="w-100 table-responsive"
                :pagination="pagination"
                :columns="columns"
                :data-source="data"
                :rowClassName="rowClassName"
            >
                <template slot="title">
                    <a-button
                        v-if="showAddButton"
                        type="primary"
                        v-on:click="add()"
                        >Add
                    </a-button>
                </template>
                <a slot="name" slot-scope="text">{{ text }}</a>
                <span slot="customTitle"><a-icon type="smile-o" /> Name</span>
                <span slot="tags" slot-scope="tags">
                    <a-tag color="volcano">
                        {{
                            tags.toUpperCase() === "COMPLETED"
                                ? "Ready For Pickup"
                                : tags.toUpperCase()
                        }}
                    </a-tag>
                </span>
                <span
                    slot="modelRender"
                    class="tag-container"
                    slot-scope="name, record"
                >
                    <span v-if="!isEmpty(record.related_products)">
                        <span
                            v-for="rProduct in record.related_products"
                            :key="rProduct.id"
                        >
                            <a-tag
                                v-if="!isEmpty(rProduct.product)"
                                color="volcano"
                                >{{ rProduct.product }}
                            </a-tag>
                        </span>
                    </span>
                </span>
                <span slot="products" slot-scope="tags, record">
                    <span v-if="!isEmpty(record.related_products)">
                        <span
                            v-for="schedule in record.related_products"
                            :key="schedule.id"
                        >
                            <a-tag
                                v-if="!isEmpty(schedule.product_id)"
                                color="volcano"
                                >{{ schedule.product.name }}
                            </a-tag>
                        </span>
                    </span>
                </span>
                <span slot="action" slot-scope="text, record">
                    <a-icon v-on:click="edit(record.id)" type="edit" />
                </span>
                <div
                    slot="filterDropdown"
                    slot-scope="{ setSelectedKeys, selectedKeys, column }"
                    style="padding: 8px"
                >
                    <a-input
                        v-ant-ref="(c) => (searchInput = c)"
                        :placeholder="`Search ${column.dataIndex}`"
                        :value="selectedKeys[0]"
                        style="width: 188px; margin-bottom: 8px; display: block"
                        @change="
                            (e) =>
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
                <div
                    slot="statusDropdown"
                    slot-scope="{ setSelectedKeys, selectedKeys, column }"
                    style="padding: 8px"
                >
                    <a-select
                        style="width: 100%; margin-bottom: 5px"
                        v-ant-ref="(c) => (searchInput = c)"
                        @change="
                            (value) => setSelectedKeys(value ? [value] : [])
                        "
                        placeholder="Select a option and change input text above"
                        @pressEnter="() => handleSearch(selectedKeys, column)"
                    >
                        <a-select-option
                            v-for="type in statuses"
                            :key="type.id"
                        >
                            {{ type.name }}</a-select-option
                        >
                    </a-select>

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
                <div
                    slot="productsDropsDown"
                    slot-scope="{ setSelectedKeys, selectedKeys, column }"
                    style="padding: 8px"
                >
                    <a-select
                        style="width: 100%; margin-bottom: 5px"
                        v-ant-ref="(c) => (searchInput = c)"
                        @change="
                            (value) => setSelectedKeys(value ? [value] : [])
                        "
                        @search="handleProductSearch"
                        show-search
                        :filter-option="false"
                        placeholder="Select a option and change input text above"
                        @pressEnter="() => handleSearch(selectedKeys, column)"
                    >
                        <a-select-option
                            v-for="type in products"
                            :key="type.id"
                        >
                            {{ type.name }}</a-select-option
                        >
                    </a-select>

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
            </a-table>
        </a-skeleton>
        <a-modal
            header="false"
            :destroyOnClose="true"
            width="95%"
            :footer="null"
            v-model="addModal"
        >
            <form-fields :repairId="repairId" @close="showAddModal()" />
        </a-modal>
    </div>
</template>

<script>
import FormFields from "./formfields";
import RepairService from "../../services/API/RepairService";
import { isEmpty } from "../../services/helpers";
import ProductService from "../../services/API/ProductService";

const columns = [
    {
        dataIndex: "id",
        key: "id",
        title: "Id",
        scopedSlots: {
            filterDropdown: "filterDropdown",
            filterIcon: "filterIcon",
        },
    },
    {
        dataIndex: "customer.name",
        key: "customerName",
        title: "Customer Name",
        scopedSlots: {
            filterDropdown: "filterDropdown",
            filterIcon: "filterIcon",
        },
    },
    {
        title: "Customer Number",
        dataIndex: "customer.phone",
        key: "customerPhone",
        scopedSlots: {
            filterDropdown: "filterDropdown",
            filterIcon: "filterIcon",
        },
    },
    {
        title: "Model",
        dataIndex: "customer.modelId",
        key: "model_name",
        scopedSlots: {
            filterDropdown: "filterDropdown",
            filterIcon: "filterIcon",
            customRender: "modelRender",
        },
        width: "200px",
    },
    {
        title: "Created At",
        dataIndex: "created_at",
        key: "created_at",
          width: "200px",
    },
     {
        title: "Updated At",
        dataIndex: "updated_at",
        key: "updated_at",
          width: "200px",
    },
    {
        title: "Status",
        key: "status",
        dataIndex: "status",
        scopedSlots: {
            filterDropdown: "statusDropdown",
            filterIcon: "filterIcon",
            customRender: "tags",
        },
    },
    {
        title: "Action",
        key: "action",
        scopedSlots: { customRender: "action" },
    },
    {
        title: "Remaining Cost",
        dataIndex: "remaining",
        key: "remaining",
    },
    {
        title: "Advance Cost",
        dataIndex: "advance_cost",
        key: "advance_cost",
    },
    {
        title: "Total Cost",
        dataIndex: "total_cost",
        key: "total_cost",
    },
];

export default {
    name: "index.vue",
    props: {
        params: { type: Object, required: false, default: () => ({}) },
        showAddButton: { type: Boolean, required: false, default: true },
    },

    data() {
        return {
            data: [],
            columns,
            addModal: false,
            deviceTypes: [],
            brands: [],
            products: [],
            loading: true,
            repairId: null,
            filters: {},
            statuses: [],
            pagination: {
                // current: 1,
                pageSize: 10,
                // total: 0,
                // showTotal: () => `Total ${this.pagination.total}`,
                onChange: (current, pageSize) =>
                    this.pageSelect(current, pageSize),
            },
        };
    },
    mounted() {
        this.fetchList();
        this.fetchStatues();
        this.fetchProducts();
        this.$emit("getFetch", this.fetchList);
    },
    methods: {
        isEmpty,
        fetchProducts(params = {}) {
            ProductService.all(params).then((response) => {
                this.products = response.data;
            });
        },
        fetchStatues() {
            RepairService.statuses().then((statuses) => {
                this.statuses = statuses;
            });
        },
        handleProductSearch(value, column) {
            this.fetchProducts({ name: value });
        },
        handleSearch(value, column) {
            let filters = this.filters;
            filters[column.key] = value[0];
            this.setfilters(filters);
        },
        handleReset(value, column) {
            let filters = this.filters;
            delete filters[column.key];
            this.setfilters(filters);
        },
        setfilters(filters) {
            this.filters = JSON.parse(JSON.stringify(filters));
            this.fetchList(this.filters);
        },
        edit(id) {
            this.repairId = id;
            this.showAddModal(true);
        },
        showAddModal(value) {
            if (!value) {
                this.fetchList();
            }
            this.addModal = value;
        },
        fetchList(param = {}) {
            this.loading = true;
            RepairService.all({ ...this.filters, ...this.pagination, ...param })
                .then((data) => {
                    this.data = data.data;
                    const pagination = { ...this.pagination };
                    pagination.current = data.current_page;
                    pagination.page = data.current_page;
                    pagination.pageSize = data.per_page;
                    pagination.total = data.total;
                    this.pagination = pagination;
                })
                .finally(() => (this.loading = false));
        },
        pageSelect(current, pageSize) {
            const pagination = { ...this.pagination };
            pagination.current = current;
            pagination.page = current;
            pagination.pageSize = pageSize;
            this.pagination = pagination;
            this.fetchList();
        },
        add() {
            this.repairId = null;
            this.showAddModal(true);
        },
        rowClassName(row) {
            console.log(row.status);
            if (row.status === "COLLECTED") {
                return "green-row";
            }
            if (row.status === "INPROGRESS") {
                return "yellow-row";
            }
            if (row.status === "CANCELLED") {
                return "red-row";
            }
            return "blue-row";
        },
    },
    components: {
        FormFields,
    },
};
</script>

<style lang="scss" scoped>
.tag-container {
    
    overflow: hidden;
   
    .ant-tag {
        white-space: inherit;
    }
}
</style>
