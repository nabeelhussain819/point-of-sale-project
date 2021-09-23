<template>
    <div>
        <a-skeleton :loading="loading">
            <a-table
                :pagination="pagination"
                :columns="columns"
                :data-source="data"
                :rowClassName="rowClassName"
            >
                <template slot="title">
                    <a-button type="primary" v-on:click="add()">Add </a-button>
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
                <span slot="action" slot-scope="text, record">
                    <a-icon v-on:click="edit(record.id)" type="edit" />
                </span>
                <div
                    slot="filterDropdown"
                    slot-scope="{
                        setSelectedKeys,
                        selectedKeys,

                        column
                    }"
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
                <div
                    slot="statusDropdown"
                    slot-scope="{ setSelectedKeys, selectedKeys, column }"
                    style="padding: 8px"
                >
                    <a-select
                        style="width: 100%; margin-bottom: 5px"
                        v-ant-ref="c => (searchInput = c)"
                        @change="value => setSelectedKeys(value ? [value] : [])"
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

const columns = [
    {
        dataIndex: "id",
        key: "id",
        title: "Id",
        scopedSlots: {
            filterDropdown: "filterDropdown",
            filterIcon: "filterIcon"
        }
    },
    {
        dataIndex: "customer.name",
        key: "customerName",
        title: "Customer Name",
        scopedSlots: {
            filterDropdown: "filterDropdown",
            filterIcon: "filterIcon"
        }
    },
    {
        title: "Customer Number",
        dataIndex: "customer.phone",
        key: "customerPhone",
        scopedSlots: {
            filterDropdown: "filterDropdown",
            filterIcon: "filterIcon"
        }
    },
    {
        title: "Created At",
        dataIndex: "created_at",
        key: "created_at"
    },
    {
        title: "Remaining Cost",
        dataIndex: "remaining",
        key: "remaining"
    },
    {
        title: "Status",
        key: "status",
        dataIndex: "status",
        scopedSlots: {
            filterDropdown: "statusDropdown",
            filterIcon: "filterIcon",
            customRender: "tags"
        }
    },
    {
        title: "Action",
        key: "action",
        scopedSlots: { customRender: "action" }
    }
];

export default {
    name: "index.vue",
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
                // pageSize: 4,
                // total: 0,
                // showTotal: () => `Total ${this.pagination.total}`,
                onChange: (current, pageSize) =>
                    this.pageSelect(current, pageSize)
            }
        };
    },
    mounted() {
        this.fetchList();
        this.fetchStatues();
    },
    methods: {
        fetchStatues() {
            RepairService.statuses().then(statuses => {
                this.statuses = statuses;
            });
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
            RepairService.all({ ...param, ...this.pagination })
                .then(data => {
                    this.data = data.data;
                    const pagination = { ...this.pagination };
                    pagination.current = response.current;
                    pagination.pageSize = response.pageSize;
                    pagination.total = response.total;
                    this.pagination = pagination;
                })
                .finally(() => (this.loading = false));
        },
        pageSelect(current, pageSize) {
            const pagination = { ...this.pagination };
            pagination.current = current;
            pagination.pageSize = pageSize;
            this.pagination = pagination;
            this.fetchList();
        },
        add() {
            this.repairId = null;
            this.showAddModal(true);
        },
        rowClassName(row) {
            if (row.status === "COLLECTED") {
                return "green-row";
            }
            return "red-row";
        }
    },
    components: {
        FormFields
    }
};
</script>

<style scoped></style>
