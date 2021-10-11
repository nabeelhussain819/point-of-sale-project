<template>
    <a-table
        class="condense-table table-bordered table-condensed"
        :data-source="data"
        :loading="loading"
        :columns="columns"
        :rowClassName="rowClassName"
        :pagination="pagination"
    >
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
                    e => setSelectedKeys(e.target.value ? [e.target.value] : [])
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
            slot="selectDropdown"
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
                <a-select-option v-for="type in types" :key="type.id">
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
                    v-for="type in installmentStatus"
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

        <a-icon
            slot="filterIcon"
            slot-scope="filtered"
            type="search"
            :style="{ color: filtered ? '#108ee9' : undefined }"
        />

        <span slot="action" slot-scope="text, record">
            <a-button v-on:click="showModal(record)" type="link">View</a-button>
        </span>
        <span slot="status" slot-scope="text, record">
            <a-tag :color="record.status.color">
                {{ record.status.name }}
            </a-tag>
        </span>
    </a-table>
</template>

<script>
import FinanceService from "../../services/API/FinanceService";
import pagination from "../../mixins/pagination";
import {
    EVENT_FINANCE_ADD_RECORD,
    EVENT_FINANCE_SHOWING_EDIT_MODAL,
    FINANCE_INSTALLMENT_STATUS,
    FINANCE_TYPE
} from "../../services/constants";

export default {
    mixins: [pagination],
    data() {
        return {
            data: [],
            searchText: "",
            searchInput: null,
            searchedColumn: "",
            types: FINANCE_TYPE,
            installmentStatus: FINANCE_INSTALLMENT_STATUS,
            columns: [
                {
                    title: "Name",
                    dataIndex: "customer_name",
                    key: "customerName",
                    scopedSlots: {
                        filterDropdown: "filterDropdown",
                        filterIcon: "filterIcon"
                    }
                },
                {
                    title: "Product",
                    dataIndex: "product.name",
                    key: "product_name",
                    scopedSlots: {
                        filterDropdown: "filterDropdown",
                        filterIcon: "filterIcon"
                    }
                },
                {
                    title: "Total",
                    dataIndex: "total",
                    key: "total"
                },
                {
                    title: "Paid",
                    dataIndex: "advance",
                    key: "advance"
                },
                {
                    title: "Remaining",
                    dataIndex: "payable",
                    key: "payable"
                },
                {
                    title: "Type",
                    dataIndex: "type",
                    key: "type",
                    filterMultiple: false,
                    scopedSlots: {
                        filterDropdown: "selectDropdown",
                        filterIcon: "filterIcon"
                    },
                    filters: []
                },
                {
                    title: "Status",
                    key: "status_id",
                    filterMultiple: false,
                    scopedSlots: {
                        filterDropdown: "statusDropdown",
                        filterIcon: "filterIcon",
                        customRender: "status"
                    }
                },
                {
                    title: "Action",
                    key: "action",
                    scopedSlots: { customRender: "action" }
                }
            ],
            loading: false,
            filters: {}
        };
    },
    methods: {
        rowClassName(row) {
            if (row.status_id === 3) {
                return "green-row";
            }
            return "red-row";
        },
        showModal(finance) {
            this.$eventBus.$emit(EVENT_FINANCE_SHOWING_EDIT_MODAL, finance);
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
            this.fetch(this.filters);
        },
        fetch(params = {}) {
            this.loading = true;
            console.log("asd");
            FinanceService.all({ ...params, ...this.pagination })
                .then(finance => {
                    this.data = finance.data;
                    this.setPagination(finance);
                })
                .finally(() => (this.loading = false));
        }
    },
    mounted() {
        let fetch = this.fetch;
        this.$emit("getFetch", fetch);
        this.$eventBus.$on(EVENT_FINANCE_ADD_RECORD, function(product) {
            if (product) {
                fetch();
            }
        });
        this.fetch();
    }
};
</script>
