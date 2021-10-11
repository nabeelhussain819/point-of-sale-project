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
                            v-decorator="[
                                `products[${record.key}][product_id]`,
                                {
                                    rules: [{ required: true }]
                                }
                            ]"
                        >
                            <a-select-option
                                :showSearch="true"
                                v-for="product in products"
                                :key="product.id"
                                >{{ product.name }}</a-select-option
                            >
                        </a-select>
                    </a-form-item>
                </div>
            </template>
            <!-- ----------- products ----------- -->
            <template slot="operation" slot-scope="text, record, index">
                <div class="editable-row-operations">
                    <a-button type="">delete</a-button>
                </div>
            </template>
        </a-table>
    </div>
</template>
<script>
import helpers from "./../../../mixins/helpers";
import ProductService from "./../../../services/API/ProductService";
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
            products: []
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
        add() {
            this.setProducts({
                quantity: 0,
                product_id: null,
                key: this.getUid(),
                quantity: 0,
                product_id: null
            });
        },
        setProducts(product) {
            this.productsList = [...this.productsList, product];
        },
        fetchProducts() {
            ProductService.getAll().then(products => {
                this.products = products;
                console.log(this.products);
            });
        }
    }
};
</script>
<style scoped>
.editable-row-operations a {
    margin-right: 8px;
}
</style>
