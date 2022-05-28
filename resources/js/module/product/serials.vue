<template>
    <div class="mt-3 serialsearch">
        <a-input-search v-model="searchSerail" placeholder="input search text" enter-button @search="onSearch" />
        <a-table :pagination="false" :loading="loading" :columns="columns" :data-source="records" :row-selection="{
            hideDefaultSelections: true,
            selectedRowKeys: selectedRowKeys,
            onChange: onSelect
        }" />
    </div>
</template>
<script>
import ProductService from "../../services/API/ProductService";
import { isEmpty } from "../../services/helpers";
const columns = [
    {
        title: "Serials",
        dataIndex: "serial_no",
        key: "serial_no"
    }
];

export default {
    props: {
        product: { default: () => { } },
        params: { default: () => { } }
    },
    data() {
        return {
            searchSerail: null,
            loading: true,
            records: [],
            columns,
            selectedRowKeys: [],
            selectedProducts: [],

        };
    },
    methods: {
        fetch() {
            ProductService.getSerials(this.product.id, {
                stock_bin_id: this.product.stock_bin_id,
                ...this.params
            })
                .then(serials => {
                    this.records = serials;
                })
                .finally(() => (this.loading = false));
        },
        onSelect(record, selected, selectedRows) {
            this.selectedRowKeys = record;

            this.emit({
                selected: record[0],
                isSelected: !isEmpty(selected),
                allSelected: selected
            });
        },
        emit(data) {
            this.$emit("onSelect", data);
        },
        onSelectAll(selected, selectedRows, record) {
            this.$emit("onSelectAll", { record, selected });
        },
        onSearch(serialKey, v) {

            const records = this.records;
            let datas = [];
            let selectedProducts = [];
            let exists = false;
            records.map((product, index) => {
                if (product.serial_no == serialKey) {
                    this.selectedProducts.map((data, index) => {
                        if (data.serial_no.includes(product.serial_no) == true) {
                            return exists = true
                        } else {
                            return exists = false
                        }
                    }
                    );
                    if (exists == true) {
                        alert('already Exists')
                    } else {
                        datas.push(index);
                        selectedProducts.push(product);
                    }
                }

            });

            this.selectedRowKeys = [...this.selectedRowKeys, ...datas];
            this.selectedProducts = [
                ...this.selectedProducts,
                ...selectedProducts
            ];

            this.searchSerail = null;
            this.emit({
                selected: null,
                isSelected: null,
                allSelected: this.selectedProducts
            });
        }
    },
    mounted() {
        this.fetch();
    },
    computed: {}
};
</script>

<style>
.serialsearch .ant-table-header-column .ant-checkbox {
    display: none;
}
</style>
