<template>
    <a-card :bordered="false">
        <a-button slot="extra" @click="add" type="primary"
            >Add Products</a-button
        >
        <a-row class="form-field-container" :gutter="23">
            <a-col :span="6">Lookup</a-col>
            <a-col :span="6">Products</a-col>
            <a-col :span="6">Quantity</a-col>
            <a-col :span="2">Unit Price</a-col>
            <a-col :span="2">Total</a-col>
            <a-col :span="2">Action</a-col>
        </a-row>
        <a-row
            v-for="(product, key) in products"
            :key="key"
            class="form-field-container"
            :gutter="23"
        >
            <a-col :span="6">
                <add-product
                    :appendData="{ key }"
                    :notInventory="true"
                    :formName="`products[${key}][lookup]`"
                    :showLabel="false"
                />
            </a-col>
            <a-col :span="6">
                <a-form-item>
                    <a-select
                        :show-search="true"
                        :filter-option="filterOption"
                        style="width: 200px"
                        v-decorator="[
                            `products[${key}][product_id]`,
                            {
                                initialValue: isEmpty(product.product_id)
                                    ? null
                                    : product.product_id,
                                rules: [
                                    {
                                        required: true,
                                        message: 'Please insert product!'
                                    }
                                ]
                            }
                        ]"
                        placeholder="products"
                    >
                        <a-select-option
                            v-for="product in inventoryProducts"
                            :key="product.id.toString()"
                        >
                            {{ product.name }}</a-select-option
                        >
                    </a-select>
                </a-form-item>
            </a-col>
            <a-col :span="6">
                <a-form-item>
                    <a-input-number
                        style="width: 200px"
                        type="number"
                        v-decorator="[
                            `products[${key}][quantity]`,
                            {
                                initialValue: isEmpty(product.quantity)
                                    ? null
                                    : product.quantity
                            }
                        ]"
                        placeholder="Quantity"
                    /> </a-form-item
            ></a-col>
            <a-col :span="2"> <a-form-item>2</a-form-item></a-col>
            <a-col :span="2"><a-form-item>2</a-form-item></a-col>
            <a-col :span="2"
                ><a-button @click="removeRow(key)" type="link"
                    ><a-icon type="delete"></a-icon></a-button
            ></a-col>
        </a-row>
    </a-card>
</template>

<script>
import axios from "axios";
import { filterOption, isEmpty } from "../../services/helpers";
import ProductService from "../../services/API/ProductService";
import AddProduct from "../product/add";
export default {
    props: {
        postetProducts: {
            default: () => {}
        }
    },

    components: {
        AddProduct
    },
    data() {
        return {
            products: {},
            uuid: 0,
            uuidString: "uuid-",
            inventoryProducts: {},
            filterOption,
            isEmpty
        };
    },
    computed: {
        t(a, b) {
            console.log(a, b);
        }
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        fetchProducts() {
            ProductService.all().then(products => {
                this.inventoryProducts = products.data;
            });
        },
        lookup(key, value) {
            return false;
        },
        getUid() {
            this.uuid = this.uuid + 1;
            return this.uuidString + this.uuid;
        },
        removeRow(key) {
            let products = this.products;

            delete products[key];
            this.updateProducts(products);
        },
        updateProducts(products) {
            products = JSON.stringify(products);
            this.products = JSON.parse(products);
        },
        add() {
            let uuidT = this.getUid();
            this.products = { ...this.products, [uuidT]: {} };
        },
        getProduct(a, b, c) {
            console.log("getProduct", a, b, c);
        },
        checkSerial(rule, value, callback, key) {
            return true;
            console.log("checkSerial");
            if (!isEmpty(value)) {
                this.cancelSearch();
                this.cancelSource = axios.CancelToken.source();
                // return ProductService.validateSerial(
                //     {
                //         product_id: prodcutId,
                //         serial_no: value
                //     },
                //     this.cancelSource.token
                // )
                //     .then(response => {
                //         this.cancelSource = null;
                //         callback();
                //         return;
                //     })
                //     .catch(e => {
                //         if (e.response.status === 404) {
                //             callback("not found");
                //         }

                //         return callback(e.response.data.message);
                //     });
            }
        },
        cancelSearch() {
            if (this.cancelSource) {
                this.cancelSource.cancel(
                    "Start new search, stop active search"
                );
            }
        }
    }
};
</script>
