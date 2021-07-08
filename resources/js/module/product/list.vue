<template>
    <a-table :columns="columns" :data-source="data">
        <a slot="name" slot-scope="text">{{ text }}</a>
        <span slot="action" slot-scope="text, record">
            <a-icon v-on:click="emitId(record.id)" type="plus" />
        </span>
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
    </a-table>
</template>
<script>
import ProductService from "../../services/API/ProductService";
const columns = [
    {
        title: "ID",
        dataIndex: "id",
        key: "id"
    },
    {
        title: "Product Name",
        dataIndex: "name",
        key: "name",
        scopedSlots: {
            filterDropdown: "filterDropdown",
            filterIcon: "filterIcon"
        }
    },
    {
        title: "Upc",
        dataIndex: "UPC",
        key: "UPC"
    },
    {
        title: "Action",
        key: "action",
        scopedSlots: { customRender: "action" }
    }
];

export default {
    data() {
        return {
            data: [],
            columns,
            filters: {}
        };
    },
    methods: {
        fetch(params = {}) {
            ProductService.getAll(params).then(products => {
                this.data = products;
            });
        },
        emitId(id) {
            this.$emit("getProduct", id);
        },
        handleSearch(value, column) {
            let filters = this.filters;
            filters[column.key] = value[0];
            this.setfilters(filters);
        },
        setfilters(filters) {
            this.filters = JSON.parse(JSON.stringify(filters));
            this.fetch(this.filters);
        },
        handleReset(value, column) {
            let filters = this.filters;
            value = "";
            delete filters[column.key];
            this.setfilters(filters);
        }
    },
    mounted() {
        this.fetch();
    }
};
</script>
