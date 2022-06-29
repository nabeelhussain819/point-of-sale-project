<template>
    <div>
        <div class="clearfix mb-2">
            <a-button type="primary" @click="add"> Add + </a-button>
        </div>
        <a-table :pagination="false" :columns="columns" :data-source="productsList" bordered>
            <!-- ----------- quantity ----------- -->
            <template slot="quantity" slot-scope="text, record">
                <div>
                    <a-form-item>
                        <a-input @change="
                            (e) => {
                                checkInventory(e, record);
                            }
                        " min="1" v-decorator="[
    `products[${record.key}][quantity]`,
    {
        rules: [{ required: true }],
    },
]" type="number" style="margin: -5px 0" />
                    </a-form-item>
                </div>
            </template>
            <!-- ----------- products ----------- -->
            <template slot="products" slot-scope="text, record">
                <div>
                    <a-form-item :validate-status="record.error.status" :help="record.error.message">
                        <a-select class="w-50" :showSearch="true" v-on:change="selectProduct" v-decorator="[
                            `products[${record.key}][product_id]`,
                            {
                                rules: [{ required: true }],
                            },
                        ]" :filter-option="filterOption">
                            <a-select-option v-for="product in products" :productHasSerial="product.has_serial_number"
                                :dataKey="record.key" :key="product.id">{{ product.name }}</a-select-option>
                        </a-select>
                        <div class="w-50 d-inline-block" v-if="record.showSerial">
                            <a-button v-on:click="showSerialModal(record)" type="primary">Associate Serial</a-button>

                            <a-tag v-for="(serial, index) in record.serials_number" :key="index" color="cyan">
                                {{ serial }}
                            </a-tag>
                            <a-tag class="d-none" color="pink"> pink </a-tag>

                            <a-modal :footer="null" v-if="viewSerialModal" :destroyOnClose="true"
                                v-model="viewSerialModal" title="">
                                <serialNumbers :params="{
                                    store_id: outStoreId(),
                                    stock_bin_id: bins.retail.id,
                                }" :product="record" @close="getSerial" />
                            </a-modal>

                            <a-select class="d-none" mode="tags" v-decorator="[
                                `products[${record.key}][serials]`,
                                {
                                    initialValue: record.serials_number,
                                    rules: [{ required: true }],
                                },
                            ]"></a-select>
                            <a-checkbox class="d-none" v-decorator="[
                                `products[${record.key}][has_serials]`,
                                {
                                    initialValue: true,
                                },
                            ]"></a-checkbox>
                        </div>
                    </a-form-item>
                </div>
            </template>
            <!-- ----------- products ----------- -->
            <template slot="operation" slot-scope="text, record">
                <div class="editable-row-operations">
                    <a-form-item>
                        <a-icon @click="removeRow(record.key)" type="delete" />
                    </a-form-item>
                </div>
            </template>
        </a-table>
    </div>
</template>
<script>
import helpers from "./../../../mixins/helpers";
import ProductService from "./../../../services/API/ProductService";
import InventoryService from "./../../../services/API/InventoryService";
import { filterOption } from "./../../../services/helpers";
import { BINS } from "./../../../services/constants";
import serialNumbers from "./serial-numbers";

const columns = [
    {
        title: "Products",
        dataIndex: "products",
        width: "75%",
        scopedSlots: { customRender: "products" },
    },
    {
        title: "Quantity",
        dataIndex: "quantity",
        width: "15%",
        scopedSlots: { customRender: "quantity" },
    },

    {
        title: "Operation",
        dataIndex: "operation",
        scopedSlots: { customRender: "operation" },
    },
];

export default {
    props: ["form"],
    components: { serialNumbers },
    mixins: [helpers],
    data() {
        return {
            columns,
            bins: BINS,
            editingKey: "",
            productsList: [],
            currentKey: {},
            uuid: 0,
            uuidString: "uuid-",
            products: [],
            filterOption,
            viewSerialModal: false,
        };
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        outStoreId() {
            const form = this.form.getFieldsValue();
            return form.store_out_id;
        },
        handleChange(value, key, column) {
            const newData = [...this.data];
            const target = newData.filter((item) => key === item.key)[0];
            if (target) {
                target[column] = value;
                this.data = newData;
            }
        },
        removeRow(key) {
            let products = this.productsList.filter(
                (product) => product.key !== key
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
                showSerial: false,
                serials_number: [],
                error: {
                    status: null,
                    message: null,
                },
            });
        },
        async showErrorOnProducts(key, quantity) {
            let products = this.productsList;
            const form = this.form.getFieldsValue();

            this.productsList = products.map((product) => {
                if (product.key === key) {
                    InventoryService.productQuantity({
                        store_out_id: form.store_out_id,
                        product_id: product.product_id,
                        quantity,
                    })
                        .then((res) => {
                            product.error.status = null;
                            product.error.message = null;
                        })
                        .catch((error) => {
                            if (error.response.status === 409) {
                                product.error.status = "error";
                                product.error.message =
                                    error.response.data.message;
                            }
                        });

                    return product;
                }
                return product;
            });
        },
        checkInventory(e, record) {
            let quantity = e.target.value;
            this.showErrorOnProducts(record.key, quantity);
        },
        setProducts(product) {
            this.productsList = [...this.productsList, product];
        },
        fetchProducts() {
            ProductService.getAll().then((products) => {
                this.products = products;
            });

        },
        selectProduct(product_id, row) {
            const hasSerial = row.data.attrs.productHasSerial;
            const key = row.data.attrs.dataKey;
            this.productsList = this.productsList.map((product) => {
                if (product.key === key) {
                    product.showSerial = hasSerial;
                    product.product_id = product_id;
                }

                return product;
            });
        },
        showSerialModal(record) {
            this.viewSerialModal = true;
        },
        getSerial(data) {
            this.productsList = this.productsList.map((product) => {
                if (product.key === data.key) {
                    product.serials_number = data.serials_number;
                }

                return product;
            });
            // this.$emit("close", this.productsList);
            this.viewSerialModal = false;
        },
    },
};
</script>
<style scoped>
.editable-row-operations a {
    margin-right: 8px;
}
</style>
