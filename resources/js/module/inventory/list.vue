<template>
    <a-table
        class="table table-bordered"
        :columns="columns"
        :data-source="data"
        :loading="loading"
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
                <a-select-option v-for="bin in bins" :key="bin.id">
                    {{ bin.name }}</a-select-option
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
        <span slot="action" slot-scope="text, record">
            <!-- <a-button
                :href="`/inventory-management/inventory/${record.id}/edit`"
                type="link"
                ><a-icon type="edit"
            /></a-button> -->
            <a-button v-on:click="showSerials(record)" type="link"
                ><a-icon type="appstore" theme="filled" />
            </a-button>
        </span>
    </a-table>
</template>
<script>
import InventoryService from "../../services/API/InventoryService";
import StockBinService from "../../services/API/StockBinService";

export default {
    data() {
        return {
            data: [],
            loading: true,
            filters: {},
            bins: {},
            columns: [
                // {
                //     title: "ID",
                //     dataIndex: "id",
                //     key: "id",
                //     scopedSlots: {
                //         filterDropdown: "filterDropdown",
                //         filterIcon: "filterIcon"
                //     }
                // },
                // {
                //     title: "Lookup",
                //     dataIndex: "UPC",
                //     key: "upc",
                //     scopedSlots: {
                //         filterDropdown: "filterDropdown",
                //         filterIcon: "filterIcon"
                //     }
                // },
                {
                    title: "Name",
                    dataIndex: "product.name",
                    key: "product_name",
                    scopedSlots: {
                        filterDropdown: "filterDropdown",
                        filterIcon: "filterIcon"
                    }
                },
                {
                    title: "Quantity",
                    dataIndex: "quantity",
                    key: "quantity"
                },
                {
                    title: "Cost",
                    key: "cost",
                    dataIndex: "cost"
                },
                {
                    title: "Extended Cost",
                    key: "extended_cost",
                    dataIndex: "extended_cost"
                },
                {
                    title: "Bin",
                    key: "stock_bin_id",
                    dataIndex: "bin.name",
                    scopedSlots: {
                        filterDropdown: "statusDropdown",
                        filterIcon: "filterIcon"
                    }
                },
                {
                    title: "Action",
                    key: "action",
                    scopedSlots: { customRender: "action" }
                }
            ]
        };
    },
    methods: {
        setfilters(filters) {
            this.filters = JSON.parse(JSON.stringify(filters));
            this.fetch(this.filters);
        },
        handleReset(value, column) {
            let filters = this.filters;
            delete filters[column.key];
            this.setfilters(filters);
        },
        handleSearch(value, column) {
            let filters = this.filters;
            filters[column.key] = value[0];
            this.setfilters(filters);
        },
        fetchBins() {
            StockBinService.get().then(bins => {
                this.bins = bins;
            });
        },
        fetch(params) {
            this.loading = true;
            InventoryService.all(params)
                .then(data => {
                    this.data = data.data;
                })
                .finally(() => (this.loading = false));
        },
        showSerials(product_id) {
            this.$emit("fetch", this.fetch);
            this.$emit("showSerialNumber", product_id);
        }
    },
    mounted() {
        this.fetchBins();
        this.fetch();
    }
};
</script>
