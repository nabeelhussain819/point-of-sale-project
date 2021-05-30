<template>
    <a-card title="Purchase Order" :bordered="false">
        <a-form :form="form" @submit="handleSubmit" layout="inline">
            <create />
            <products-form-field :postetProducts="products" />
            <a-form-item>
                <a-button type="primary" :loading="loading" htmlType="submit"
                    >Submit</a-button
                >
            </a-form-item>
        </a-form>
    </a-card>
</template>
<script>
import create from "./create-form-field";
import ProductsFormField from "./products-form-fields";

import { EVENT_CUSTOMERSALE_PRODUCT_ADD } from "../../services/constants";
import { isEmpty } from "../../services/helpers";
export default {
    components: { create, ProductsFormField },
    data() {
        return {
            loading: false,
            form: this.$form.createForm(this, { name: "binTransfer" }),
            products: {}
        };
    },
    methods: {
        handleSubmit(e) {
            e.preventDefault();
            this.form.validateFields((err, values) => {
                if (!err) {
                    console.log(values);
                }
            });
        },
        setProduct(product) {            
            let key = product.appendData.key;
            if (!isEmpty(key)) {
                let formProduct = this.form.getFieldValue("products");

                console.log(formProduct);
                formProduct[key].prices = product.retail_price;
                formProduct[key].quantity = 3;
                formProduct[key].product_id = "1001";

                this.products = formProduct;
            }
        }
    },
    mounted() {
        let setProducts = this.setProduct;
        this.$eventBus.$on(EVENT_CUSTOMERSALE_PRODUCT_ADD, function(product) {
            setProducts(product);
        });
    }
};
</script>
