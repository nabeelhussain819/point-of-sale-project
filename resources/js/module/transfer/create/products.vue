<template>
    <div>
        <div class="clearfix">
            <a-button type="primary" @click="add">
                Add +
            </a-button>
        </div>
        <a-table :columns="columns" :data-source="productsList" bordered>
            <!-- ----------- quantity ----------- -->
            <template slot="quantity" slot-scope="text, record, index">
                <div>
                    <a-form-item>
                        <a-input
                            v-decorator="[
                                `products[${record.key}][quantity]`,
                                {
                                    rules: [{ required: true }]
                                }
                            ]"
                            type="number"
                            style="margin: -5px 0"
                        />
                    </a-form-item>
                </div>
            </template>
            <!-- ----------- products ----------- -->
            <template slot="products" slot-scope="text, record, index">
                <div>
                    <a-form-item>
                        <a-select
                            :showSearch="true"
                            v-on:change="selectProduct"
                            v-decorator="[
                                `products[${record.key}][product_id]`,
                                {
                                    rules: [{ required: true }]
                                }
                            ]"
                            :filter-option="filterOption"
                        >
                            <a-select-option
                                v-for="product in products"
                                :productHasSerial="product.has_serial_number"
                                :dataKey="record.key"
                                :key="product.id"
                                >{{ product.name }}</a-select-option
                            >
                        </a-select>
                        <a-button
                            v-if="record.showSerial"
                            v-on:click="showSerialModal(record)"
                            type="primary"
                            >Associate Serial</a-button
                        >
                    </a-form-item>
                </div>
            </template>
            <!-- ----------- products ----------- -->
            <template slot="operation" slot-scope="text, record, index">
                <div class="editable-row-operations">
                    <a-button @click="removeRow(record.key)" type=""
                        >delete</a-button
                    >
                </div>
            </template>
        </a-table>
    </div>
</template>
<script>
import helpers from "./../../../mixins/helpers";
import ProductService from "./../../../services/API/ProductService";
import { filterOption } from "./../../../services/helpers";
const columns = [
    {
        title: "Quantity",
        dataIndex: "quantity",
        width: "15%",
        scopedSlots: { customRender: "quantity" }
    },
    {
        title: "Products",
        dataIndex: "products",
        width: "45%",
        scopedSlots: { customRender: "products" }
    },
    {
        title: "Operation",
        dataIndex: "operation",
        scopedSlots: { customRender: "operation" }
    }
];

export default {
    mixins: [helpers],
    data() {
        return {
            columns,
            editingKey: "",
            productsList: [],
            currentKey: {},
            uuid: 0,
            uuidString: "uuid-",
            products: [],
            filterOption
        };
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        handleChange(value, key, column) {
            const newData = [...this.data];
            const target = newData.filter(item => key === item.key)[0];
            if (target) {
                target[column] = value;
                this.data = newData;
            }
        },
        removeRow(key) {
            let products = this.productsList.filter(
                product => product.key !== key
            );
            this.productsList = products;
        },
        add() {
            this.setProducts({
                quantity: 0,
                product_id: null,
                key: this.getUid(),
                quantity: 0,
                product_id: null,
                showSerial: false
            });
        },
        setProducts(product) {
            this.productsList = [...this.productsList, product];
        },
        fetchProducts() {
            ProductService.getAll().then(products => {
                this.products = products;
            });
        },
        selectProduct(record, row) {
            const hasSerial = row.data.attrs.productHasSerial;
            const key = row.data.attrs.dataKey;
            this.productsList = this.productsList.map(product => {
                if (product.key === key) {
                    product.showSerial = hasSerial;
                }
                product.product_id = record;
                return product;
            });
        },
        showSerialModal(record) {
            console.log(record);
        }
    }
};
</script>
<style scoped>
.editable-row-operations a {
    margin-right: 8px;
}
</style>
