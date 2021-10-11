<template>
    <div class="mt-3">
        <a-input-search
            v-model="searchSerail"
            placeholder="input search text"
            enter-button
            @search="onSearch"
        />
        <a-table
            :loading="loading"
            :columns="columns"
            :data-source="records"
            :row-selection="{
                selectedRowKeys: selectedRowKeys,
                onChange: onSelect
            }"
        />
    </div>
</template>
<script>
import ProductService from "../../services/API/ProductService";
const columns = [
    {
        title: "Serials",
        dataIndex: "serial_no",
        key: "serial_no"
    }
];

export default {
    props: {
        product: { default: () => {} }
    },
    data() {
        return {
            searchSerail: null,
            loading: true,
            records: [],
            columns,
            selectedRowKeys: [
                { id: 26, product_id: 1002, serial_no: "z6", store_id: 1 }
            ]
        };
    },
    methods: {
        fetch() {
            ProductService.getSerials(this.product.id, {
                stock_bin_id: this.product.stock_bin_id
            })
                .then(serials => {
                    this.records = serials;
                })
                .finally(() => (this.loading = false));
        },
        onSelect(record, selected, selectedRows) {
            this.$emit("onSelect", { record, selected });
            this.selectedRowKeys = record;
        },
        onSelectAll(selected, selectedRows, record) {
            this.$emit("onSelectAll", { record, selected });
        },
        onSearch(serialKey, v) {
            const records = this.records;
            let datas = [];
            records.map((product, index) => {
                if (product.serial_no == serialKey) {
                    datas.push(index);
                }
            });
            this.selectedRowKeys = [...this.selectedRowKeys, ...datas];
            this.searchSerail = null;
        }
    },
    mounted() {
        this.fetch();
    },
    computed: {}
};
</script>
