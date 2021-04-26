<template>
    <a-table
        class="table table-bordered"
        :columns="columns"
        :data-source="data"
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
        <span slot="action" slot-scope="text, record">
            <a-button
                :href="`/inventory-management/inventory/${record.id}/edit`"
                type="link"
                ><a-icon type="edit"
            /></a-button>
            <a-button
                v-on:click="showSerials(record.product_id)"
                v-if="record.product.has_serial_number"
                type="link"
                ><a-icon type="appstore" theme="filled" />
            </a-button>
        </span>
    </a-table>
</template>
<script>
import InventoryService from "../../services/API/InventoryService";
export default {
    data() {
        return {
            data: [],
            columns: [
                {
                    title: "ID",
                    dataIndex: "id",
                    key: "id",
                    scopedSlots: {
                        filterDropdown: "filterDropdown",
                        filterIcon: "filterIcon"
                    }
                },
                {
                    title: "Lookup",
                    dataIndex: "UPC",
                    key: "upc",
                    scopedSlots: {
                        filterDropdown: "filterDropdown",
                        filterIcon: "filterIcon"
                    }
                },
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
                    dataIndex: "stock_bin_id",
                    scopedSlots: {
                        filterDropdown: "filterDropdown",
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
        handleSearch() {},
        fetch(params) {
            InventoryService.list(params).then(data => {
                this.data = data.data;
            });
        },
        showSerials(product_id) {
            this.$emit("showSerialNumber", product_id);
        }
    },
    mounted() {
        this.fetch();
    }
};
</script>
