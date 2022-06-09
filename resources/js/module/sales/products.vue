<template>
    <div>
        <a-row class="form-field-container" :gutter="23">
            <a-col :span="1"><strong>Id</strong></a-col>
            <a-col :span="7"><strong>Name</strong></a-col>

            <a-col :span="4"><strong>Serial</strong></a-col>
            <a-col :span="2"><strong>Quantity</strong></a-col>
            <a-col :span="3"><strong>Discount</strong></a-col>
            <a-col :span="2"><strong>Unit Prices</strong></a-col>
            <a-col :span="2"><strong>Extended Prices</strong></a-col>
            <a-col :span="2"><strong>Total</strong></a-col>
            <a-col :span="1"><strong></strong></a-col>
        </a-row>
        <a-row v-for="(product, key) in products" :key="key" class="form-field-container" :gutter="23">
            <a-col :span="1"> {{ product.id }}</a-col>
            <a-col :span="7"> {{ product.name }}</a-col>

            <a-col :span="4">
                <a-form-item v-if="product.has_serial_number">
                    <a-input-search v-on:blur="value => serialNumberHandling(key, value)"
                        :disabled="!product.has_serial_number" v-decorator="[
                            `productItem[${key}][serial_number]`,
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
                        ]" @search="serialModal(product, key)">
                        <a-button type="primary" slot="enterButton">
                            <a-icon type="mobile" />
                        </a-button>
                    </a-input-search>
                </a-form-item>
                <span v-else> No Serial</span>
            </a-col>
            <a-col :span="2">
                <a-form-item>
                    <a-input @change="
                        e => {
                            computedTotal(e, key);
                        }
                    " :disabled="product.has_serial_number" type="number" v-decorator="[
    `productItem[${key}][quantity]`,
    {
        initialValue: product.quantity
    }
]" />
                </a-form-item>
            </a-col>
            <a-col :span="3">
                <a-form-item>
                    <a-input :max="100" prefix="%" type="number" @change="e => dicountreal(e, key)" v-decorator="[
                        `productItem[${key}][discount]`,
                        {
                            initialValue: 0,
                            rules: []
                        }
                    ]" />
                </a-form-item>
            </a-col>
            <a-col :span="2">
                <a-form-item>
                    <a-input type="number" v-decorator="[
                        `productItem[${key}][price]`,
                        {
                            initialValue: 0,
                            initialValue: product.retail_price,
                            rules: []
                        }
                    ]" />
                </a-form-item>
            </a-col>
            <a-col :span="2">
                <a-form-item>
                    <a-input @change="
                        e => {
                            cost(e, key);
                        }
                    " type="number" v-decorator="[`productItem[${key}][min_price]`, {
    initialValue: product.min_price,
    rules: []
}
]" />
                </a-form-item>
            </a-col>
            <a-col :span="2">
                <a-form-item>
                    {{ product.total }}
                </a-form-item>
            </a-col>
            <a-col :span="1">
                <a-button v-on:click="removeRow(key)" type="link">
                    <a-icon type="delete" />
                </a-button>
            </a-col>
        </a-row>
    </div>
</template>
<script>
import {
    EVENT_CUSTOMERSALE_PRODUCT_SUMMARY,
    EVENT_CUSTOMERSALE_PRODUCT_ADD
} from "../../services/constants";
import axios from "axios";
import ProductService from "../../services/API/ProductService";
import { isEmpty } from "../../services/helpers";
export default {
    props: {
        preloadProduct: {
            default: null
        },
        form: {
            default: () => { }
        }
    },
    data() {
        return {
            products: {},
            uuid: 0,
            uuidString: "uuid-",
            total: 0,
            expendedTotal: 0,
            showSerialModal: false,
            selectedProduct: {},
            cancelSource: null,
            productPrice: 0,
        };
    },
    methods: {
        handleSubmit() { },
        getUid() {
            this.uuid = this.uuid + 1;
            return this.uuidString + this.uuid;
        },
        setProducts(product) {
            if (isEmpty(product.quantity)) {
                product.quantity = 1;
            }
            let uuidT = this.getUid();
            let products = { ...this.products, [uuidT]: product };
            this.products = products;
            this.updateProducts(products);

            this.computedTotal(product.quantity, uuidT);
        },
        removeRow(key) {
            let products = this.products;

            delete products[key];
            this.updateProducts(products);
        },
        computedTotal(event, key) {
            let quantity = isEmpty(event.target) ? event : event.target.value;
            this.updateQuantity(quantity, key);
        },
        updateQuantity(quantity, key) {
            let pp = this.products;

            let number = quantity * this.productPrice;

            pp[key].total = ((Math.round(number * 100) / 100)).toFixed(2);
            console.log(pp[key].total);
            pp[key].quantity = parseFloat(quantity);

            this.updateProducts(pp);
        },
        checkSerial(rule, value, callback, key) {
            if (!isEmpty(value)) {
                this.cancelSearch();
                let prodcutId = this.products[key].id;
                this.cancelSource = axios.CancelToken.source();
                return ProductService.validateSerial(
                    {
                        product_id: prodcutId,
                        serial_no: value
                    },
                    this.cancelSource.token
                )
                    .then(response => {
                        this.cancelSource = null;
                        callback();
                        return;
                    })
                    .catch(e => {
                        if (e.response.status === 404) {
                            callback("not found");
                        }

                        return callback(e.response.data.message);
                    });
            }
            callback();
        },
        cancelSearch() {
            // console.log();
            // if (this.cancelSource) {
            //     this.cancelSource.cancel(
            //         "Start new search, stop active search"
            //     );
            // }
        },
        cost(value, key) {
            value = value.target.value;
            let pp = this.products;

            pp[key].min_price = value;

            this.updateProducts(pp);
            this.updateQuantity(pp[key].quantity, key);
        },
        dicountreal(value, key) {
            value = value.target.value;
            let pp = this.products;
            let formPP = this.form.getFieldValue("productItem");
            let price = formPP[key].min_price;
            let dicountFormula = (price / 100) * value;
            this.productPrice = price - dicountFormula;

            this.updateProducts(pp);
            this.updateQuantity(pp[key].quantity, key);

        },
        // discount(value, key) {
        //     value = value.target.value;
        //     let pp = this.products;
        //     let formPP = this.form.getFieldValue("productItem");

        //     this.productPrice = formPP[key].min_price;

        //     pp[key].min_price = this.dicountFormula(this.productPrice, value);

        //     this.updateProducts(pp);
        //     this.updateQuantity(pp[key].quantity, key);
        // },
        // dicountFormula(prices, discountPrices) {
        //     console.log(prices, discountPrices)
        //     let dicountFormula = (prices / 100) * discountPrices;

        //     return prices - dicountFormula;
        // },
        updateProducts(products) {
            products = JSON.stringify(products);
            this.products = JSON.parse(products);

            this.$eventBus.$emit(
                EVENT_CUSTOMERSALE_PRODUCT_SUMMARY,
                this.products
            );
        },
        serialModal(product, key) {
            return false;
            product.key = key;
            this.selectedProduct = product;

            this.handleSearialModal(true);
        },
        handleSearialModal(show) {
            this.showSerialModal = show;
        },
        getSerial(product) {
            let products = this.products;
            products[this.selectedProduct.key].serial_number =
                product.serial_no;
            this.updateProducts(products);
            this.handleSearialModal(false);
        },

        serialNumberHandling(key, value) {
            console.log("asd", key, value);
            let products = this.products;
            products[key].serial_number = value.target.value;
            this.updateProducts(products);
        }
    },
    mounted() {
        //  @todo promise remove settime interval
        let setProducts = this.setProducts;
        if (this.preloadProduct) {
            setTimeout(() => {
                setProducts(this.preloadProduct.product);
            }, 300);
        }
        this.$eventBus.$on(EVENT_CUSTOMERSALE_PRODUCT_ADD, function (product) {
            setProducts(product);
        });
    }
};
</script>
<style scoped>
.d-none {
    display: none;
}
</style>

<style lang="scss" scoped>
.form-field-container.ant-row>.ant-col {
    border: 1px solid #e2e2e2;
    height: 59px;
    vertical-align: middle;
    display: flex;
    justify-content: center;
    align-items: center;
}

.form-field-container .ant-form-item {
    margin-bottom: 0;
}
</style>
