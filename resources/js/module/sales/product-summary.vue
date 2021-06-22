<template>
    <a-row class="bg-blue-container text-bold">
        <a-col class="mt-3" :span="14">
            <a-descriptions>
                <a-descriptions-item label="Sub Total">
                    ${{ withoutTax }}
                </a-descriptions-item>
                <!-- <a-descriptions-item label="Without Tax">
                    {{ withoutTax }}
                </a-descriptions-item> -->
                <a-descriptions-item label="Saving">
                    $ {{ discount }}
                </a-descriptions-item>
                <a-descriptions-item label="Tax %">
                    <a-input-number
                        :min="0"
                        :max="100"
                        v-on:change="getTaxChange"
                        :value="tax"
                    />
                </a-descriptions-item> </a-descriptions
        ></a-col>
        <a-col v-on:click="handleSubmit" class="checkout-box" :span="10">
            <!-- <a-button v-on:click="toggleModal(true)" type="default">
                </a-button
            > -->
            <span
                ><a-icon class="cart-icon" type="shopping-cart" /> Apply
                Discount & CheckOut</span
            >
            <a-divider type="vertical" />
            Total: $ <span>{{ subTotal }}</span>

            <!-- <a-popconfirm
                title="Are you sure cancel this transaction?"
                ok-text="Yes"
                cancel-text="No"
                @confirm="confirm"
                @cancel="cancel"
            >
                <a-button type="danger"> Cancel Transcation </a-button>
            </a-popconfirm> -->
        </a-col>
        <a-modal
            :destroyOnClose="true"
            width="100%"
            :visible="showOrderInvoice"
            @cancel="toggleModal(false)"
            :dialog-style="{ top: '1px' }"
            title=""
            footer=""
            ><product-table
                @orderCreated="orderCreated"
                :products="products"
                :customer="customer"
                :billSummary="billSummary"
        /></a-modal>
    </a-row>
</template>

<script>
import { isEmpty, objectToArray } from "../../services/helpers";
import {
    EVENT_CUSTOMERSALE_PRODUCT_SUMMARY,
    EVENT_CUSTOMERSALE_CUSTOMER_DETAIL
} from "../../services/constants";
import productTable from "./products-table";
import StoreService from "../../services/API/StoreService";
export default {
    props: {
        form: { default: () => {} }
    },
    components: { productTable },
    data() {
        return {
            showOrderInvoice: false,
            products: [],
            emitedProducts: {},
            subTotal: 0,
            discount: 0,
            customer: null,
            tax: null,
            withoutTax: 0,
            withoutDiscount: 0,
            billSummary: {},
            isOrderCreated: false
        };
    },
    methods: {
        orderCreated(created) {
            this.isOrderCreated = created;
        },
        toggleModal($show) {
            if (false === $show && this.isOrderCreated) {
                window.location.href = "/sales/create";
            }
            this.showOrderInvoice = $show;
        },
        confirm(e) {
            //this.$message.success("Click on Yes");
        },
        cancel(e) {
            //this.$message.error("Click on No");
        },
        calculate(products) {
            this.emitedProducts = products;
            let total = 0;
            let withoutDiscount = 0;
            this.products = objectToArray(products);
            for (const key in products) {
                withoutDiscount +=
                    products[key].quantity *
                    parseFloat(products[key].retail_price);
                total += parseFloat(products[key].total);
            }

            this.withoutDiscount = withoutDiscount;

            this.discount = withoutDiscount - total; // total value discount
            this.discount = parseFloat(this.discount).toFixed(2);
            this.withoutTax = parseFloat(total);

            this.subTotal = this.getTotalwithTax(
                total,
                this.getTaxValue(total)
            );

            this.billSummary = {
                discount: this.discount,
                withoutTax: this.withoutTax,
                subTotal: this.subTotal,
                withoutDiscount: this.withoutDiscount,
                tax: this.tax
            };
        },
        getTotalwithTax(total, taxPrice) {
            taxPrice = parseFloat(total + taxPrice);
            return taxPrice.toFixed(2);
        },
        getTaxValue(retailPrice) {
            return (retailPrice / 100) * this.tax;
        },
        setCustomer(customer) {
            this.customer = customer;
        },
        getTax() {
            StoreService.currentTax().then(data => {
                this.tax = data.tax;

                if (data.tax == null || data.tax == undefined) {
                    this.tax = 0;
                }
            });
        },
        getTaxChange(tax) {
            this.tax = tax;
            this.calculate(this.emitedProducts);
        },
        handleSubmit() {
            if (isEmpty(this.products)) {
                return false;
            }
            this.$emit("handleSubmit", this.toggleModal);
        }
    },
    mounted() {
        let calc = this.calculate;
        let setCustomer = this.setCustomer;
        this.$eventBus.$on(EVENT_CUSTOMERSALE_PRODUCT_SUMMARY, function(
            products
        ) {
            calc(products);
            // this.products = products;
        });
        this.$eventBus.$on(EVENT_CUSTOMERSALE_CUSTOMER_DETAIL, function(
            customer
        ) {
            setCustomer(customer);
        });
        this.getTax();
    }
};
</script>
<style scoped>
.cart-icon {
    font-size: 2em;
    text-align: center;
}
</style>
