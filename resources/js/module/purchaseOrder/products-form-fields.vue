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
            <a-col :span="2"></a-col>
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
                        @select="selectProduct"
                        v-decorator="[
                            `products[${key}][product_id]`,
                            {
                                initialValue: product.product_id,
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
                            v-for="invProduct in inventoryProducts"
                            :key="invProduct.id"
                            :price="invProduct.retail_price"
                            :index="key"
                        >
                            {{ invProduct.name }}</a-select-option
                        >
                    </a-select>
                </a-form-item>
            </a-col>
            <a-col :span="6">
                <a-form-item>
                    <a-input-number
                        style="width: 200px"
                        type="number"
                        :min="1"
                        v-decorator="[
                            `products[${key}][quantity]`,
                            {
                                initialValue: product.quantity,
                                rules: [
                                    {
                                        required: true,
                                        message: 'Please insert product!'
                                    }
                                ]
                            }
                        ]"
                        placeholder="Quantity"
                    /> </a-form-item
            ></a-col>
            <a-col :span="2">
                <a-form-item>
                    <a-input-number
                        :disabled="true"
                        :min="1"
                        v-decorator="[
                            `products[${key}][price]`,
                            {
                                initialValue: product.retail_price,
                                rules: [
                                    {
                                        required: true,
                                        message: 'Please insert prices!'
                                    }
                                ]
                            }
                        ]"
                    ></a-input-number></a-form-item
            ></a-col>
            <a-col :span="2"><a-form-item></a-form-item></a-col>
            <a-col :span="2"
                ><a-button @click="removeRow(key)" type="link"
                    ><a-icon type="delete"></a-icon></a-button
            ></a-col>
        </a-row>
    </a-card>
</template>

<script>
import { filterOption, isEmpty } from "../../services/helpers";
import ProductService from "../../services/API/ProductService";
import AddProduct from "../product/add";
import { EVENT_CUSTOMERSALE_PRODUCT_ADD } from "../../services/constants";

export default {
    props: {},
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
    mounted() {
        this.fetchProducts();
        let setProducts = this.setProduct;
        this.$eventBus.$on(EVENT_CUSTOMERSALE_PRODUCT_ADD, function(product) {
            setProducts(product);
        });
    },
    methods: {
        selectProduct(id, option) {
            let prices = option.data.attrs.price;
            let key = option.data.attrs.index;
            let products = this.products;
            products[key].retail_price = prices;
            this.updateProducts(products);
        },
        fetchProducts() {
            ProductService.getAll().then(products => {
                this.inventoryProducts = products;
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
        cancelSearch() {
            if (this.cancelSource) {
                this.cancelSource.cancel(
                    "Start new search, stop active search"
                );
            }
        },
        setProduct(product) {
            let key = product.appendData.key;
            if (!isEmpty(key)) {
                let formProduct = JSON.stringify(this.products);
                formProduct = JSON.parse(formProduct);
                formProduct[key].retail_price = product.retail_price;
                formProduct[key].product_id = product.id;
                this.updateProducts(formProduct);
            }
        }
    }
};
</script>
