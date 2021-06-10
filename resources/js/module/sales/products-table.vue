<template>
    <div class="checkout-container">
        <div class="no-print">
            <a-row class="m-2 ">
                <a-col :span="12">
                    <h1 class="heading only-for-print">XYZ Company</h1>
                    <span class="customer-detail">
                        Customer :
                        <span>
                            <span v-if="!isEmpty(customer)">{{
                                customer.name_phone
                            }}</span>
                            <span v-else>WalkIn Customer</span>
                        </span>
                    </span>
                </a-col>
                <a-col :span="12">
                    <h1 class="heading only-for-print">
                        Invoice #{{ order.id }}
                    </h1>
                    <a-descriptions class="only-for-print" bordered>
                        <a-descriptions-item label="Date" :span="24">
                            {{ currentDateTime }}
                        </a-descriptions-item>
                        <!-- <a-descriptions-item :span="24" label="Due Date">
            {{ currentDateTime }}
          </a-descriptions-item> -->
                    </a-descriptions>
                </a-col>
            </a-row>

            <a-table
                :columns="columns"
                :pagination="false"
                :data-source="products"
            >
                <a slot="name" slot-scope="text">{{ text }}</a>
            </a-table>
            <br /><br />
            <a-row>
                <a-col :span="8">
                    <a-form
                        :form="form"
                        @submit="handleSubmit"
                        :label-col="{ span: 24 }"
                        :wrapper-col="{ span: 24 }"
                    >
                        <checkout
                            v-if="!isCreated"
                            @cashBack="cashBack"
                            :summary="billSummary"
                        />
                        <a-button
                            v-if="isEmpty(order) && !isCreated"
                            class="no-print"
                            type="primary"
                            html-type="submit"
                            >Checkout</a-button
                        >
                        <a-button class="no-print" @click="print" type="button"
                            >Print</a-button
                        >
                    </a-form>
                </a-col>
                <a-col :span="8"></a-col>
                <a-col :span="8">
                    <a-descriptions bordered title="Bill summary">
                        <!-- <a-descriptions-item :span="24" label="Without Tax">
            {{ billSummary.withoutTax }}
          </a-descriptions-item> -->
                        <a-descriptions-item :span="24" label="Total Discount">
                            $ {{ billSummary.discount }}
                        </a-descriptions-item>
                        <!-- <a-descriptions-item :span="24" label="Without Discount">
            ${{ billSummary.withoutDiscount }} 
          </a-descriptions-item> -->
                        <a-descriptions-item :span="24" label="Tax">
                            {{ billSummary.tax }} %
                        </a-descriptions-item>
                        <a-descriptions-item label="Sub Total" :span="24">
                            ${{ billSummary.subTotal }}
                        </a-descriptions-item>
                    </a-descriptions>
                </a-col>
            </a-row>
        </div>
        <printable
            :printDetail="printDetail"
            :products="products"
            :order="order"
            :customer="customer"
            :billSummary="billSummary"
        />
    </div>
</template>
<script>
import {
    isEmpty,
    notification,
    errorNotification
} from "../../services/helpers";
import OrderService from "../../services/API/OrderServices";
import checkout from "./checkout";
import printable from "./printable";
import moment from "moment";
const columns = [
    {
        title: "Name",
        dataIndex: "name",
        key: "name",
        ellipsis: true
    },
    {
        title: "Serial",
        dataIndex: "serial_number",
        key: "serial_number"
    },
    {
        title: "Unit Prices",
        dataIndex: "retail_price",
        key: "retail_price"
    },
    {
        title: "Extended Price",
        dataIndex: "min_price",
        key: "min_price"
    },
    {
        title: "Quantity",
        dataIndex: "quantity",
        key: "quantity"
    }
];

export default {
    components: {
        checkout,
        printable
    },
    props: {
        isCreated: { default: false },
        products: { default: () => [] },
        customer: { default: () => {} },
        billSummary: { default: () => {} },
        createdOrder: { default: () => {} }
    },
    data() {
        return {
            formLayout: "horizontal",
            form: this.$form.createForm(this, { name: "addCustomer" }),
            columns,
            isEmpty,
            currentDateTime: moment().format("MMMM Do YYYY, h:mm:ss a"),
            order: {},
            printDetail: {}
        };
    },
    methods: {
        fetchPrintDetail() {
            OrderService.print().then(printDetail => {
                this.printDetail = printDetail;
            });
        },
        handleSubmit(e) {
            e.preventDefault();
            this.form.validateFields((err, values) => {
                if (!err) {
                    let data = {
                        products: this.products,
                        customer: this.customer,
                        summary: this.billSummary,
                        sales: values
                    };
                    OrderService.create(data)
                        .then(response => {
                            this.$emit("orderCreated", true);
                            this.order = response;
                            notification(this, response.message);
                            setTimeout(function() {
                                this.print();
                            }, 1000);
                        })
                        .catch(error => {
                            errorNotification(this, error);
                        });
                }
            });
        },
        cashBack(value) {
            this.form.setFieldsValue({ cash_back: value });
        },
        print() {
            window.print();
        }
    },
    mounted() {
        this.fetchPrintDetail();
        if (!isEmpty(this.createdOrder)) {
            this.order = this.createdOrder;
        }
    }
};
</script>

<style lang="scss">
@media print {
    .no-print {
        display: none;
    }
}
</style>
