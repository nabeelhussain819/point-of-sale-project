<template>
    <a-card :bordered="false">
        <a-button slot="extra" @click="add" type="primary"
            >Add Products</a-button
        >
        <a-row
            v-for="(product, key) in products"
            :key="key"
            class="form-field-container"
            :gutter="23"
        >
            <a-col :span="6">
                <a-form-item>
                    <a-input-search
                        placeholder="Lookup"
                        v-on:blur="value => lookup(key, value)"
                        v-decorator="[
                            `product[${key}][lookup]`,
                            {
                                initialValue: product.serial_number,
                                rules: [
                                    {
                                        required: true,
                                        message: 'Please insert serial number!'
                                    },
                                    {
                                        validator: (rule, value, callback) =>
                                            checkSerial(
                                                rule,
                                                value,
                                                callback,
                                                key
                                            )
                                    }
                                ]
                            }
                        ]"
                        @search="getProduct(product, key)"
                    >
                        <a-button type="primary" slot="enterButton">
                            <a-icon type="mobile" />
                        </a-button>
                    </a-input-search>
                </a-form-item>
            </a-col>
            <a-col :span="6">
                <a-form-item>
                    <a-select
                        :show-search="true"
                        :filter-option="filterOption"
                        style="width: 200px"
                        v-decorator="[
                            `product[${key}][product_id]`,
                            {
                                rules: [
                                    {
                                        required: true,
                                        message: 'Please insert status!'
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
                            `product[${key}][quantity]`,
                            {
                                initialValue: product.quantity
                            }
                        ]"
                        placeholder="Quantity"
                    /> </a-form-item
            ></a-col>
            <a-col :span="2"> <a-form-item>2</a-form-item></a-col>
            <a-col :span="2"><a-form-item>2</a-form-item></a-col>
            <a-col :span="2"
                ><a-button type="link"><a-icon type="delete"></a-icon></a-button
            ></a-col>
        </a-row>
    </a-card>
</template>

<script>
import axios from "axios";

import { filterOption, isEmpty } from "../../services/helpers";
import ProductService from "../../services/API/ProductService";
export default {
    data() {
        return {
            products: {},
            uuid: 0,
            uuidString: "uuid-",
            inventoryProducts: {},
            filterOption
        };
    },
    mounted() {
        this.fetchProducts();
    },
    methods: {
        fetchProducts() {
            ProductService.all().then(products => {
                console.log(products);
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
