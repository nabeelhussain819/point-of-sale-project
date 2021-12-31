<template>
    <div class="no-print">
        <a-row :gutter="2">
            <!-- <a-form :form="form" :label-col="{ span: 24 }" :wrapper-col="{ span: 24 }"> -->
            <!-- <a-radio-group
                class="no-print"
                name="radioGroup"
                @change="paymentMode"
                default-value="cash"
            >
                <a-radio value="cash"> Cash </a-radio>
                <a-radio value="card"> Card </a-radio>
            </a-radio-group> -->
            <br /><br />

            <a-col :span="18">
                <a-form-item label="Card Number">
                    <a-input
                        @change="cardNumber"
                        type="card_number"
                        v-decorator="[
                            'customer_card_number',
                            {
                                rules: [
                                    {
                                        required: !isCash,
                                        message:
                                            'Please input your customer card number!'
                                    }
                                ]
                            }
                        ]"/></a-form-item
            ></a-col>
            <a-col :span="6">
                <a-form-item label="Card Amount">
                    <!-- ------------------- -->

                    <a-input-number
                        @change="cardNumber"
                        type="number"
                        v-decorator="[
                            'card_paid',
                            {
                                rules: [
                                    {
                                        required: !isCash,
                                        message: 'Please input your amount!'
                                    },
                                    {
                                        validator: (rule, value, callback) =>
                                            validateTotal(
                                                rule,
                                                value,
                                                callback,
                                                'card_paid'
                                            )
                                    }
                                ]
                            }
                        ]"
                    />
                    <!-- ------------------------------ -->
                </a-form-item></a-col
            >
            <a-col :span="18">
                <a-form-item label="Cash Back">
                    <!-- ------------------- -->
                    <a-input
                        :disabled="true"
                        type="number"
                        v-decorator="[
                            'cash_back',
                            {
                                rules: []
                            }
                        ]"
                    />
                    <!-- ------------------------------ -->
                </a-form-item></a-col
            >
            <a-col :span="6">
                <a-form-item label="Amount">
                    <!-- ------------------- -->

                    <a-input-number
                        v-on:change="getAmount"
                        type="number"
                        v-decorator="[
                            'cash_paid',
                            {
                                rules: [
                                    {
                                        required: isCash,
                                        message: 'Please input your amount!'
                                    },
                                    {
                                        validator: (rule, value, callback) =>
                                            validateTotal(
                                                rule,
                                                value,
                                                callback,
                                                'cash_paid'
                                            )
                                    }
                                ]
                            }
                        ]"
                    />
                    <!-- ------------------------------ -->
                </a-form-item></a-col
            >

            <a-col :span="24">
                <a-form-item label="Notes">
                    <!-- ------------------- -->
                    <a-textarea
                        type="number"
                        v-decorator="[
                            'notes',
                            {
                                rules: []
                            }
                        ]"
                    ></a-textarea>
                    <!-- ------------------------------ -->
                </a-form-item></a-col
            >

            <!-- </a-form> -->
        </a-row>
    </div>
</template>

<script>
import { isEmpty } from "../../services/helpers";
export default {
    props: { summary: { default: () => {} }, form: { default: () => ({}) } },
    data() {
        return {
            isCash: true
        };
    },
    methods: {
        cardNumber(e, b) {
            if (!isEmpty(e.target)) {
                this.isCash = isEmpty(e.target.value);
            }
            this.isCash = isEmpty(e);
        },
        getMin() {
            let total = this.summary.subTotal;
            if (!isEmpty(total)) {
                return parseFloat(total);
            }
            return 0;
        },
        paymentMode(e) {
            this.isCash = e.target.value === "cash";
        },
        validateTotal(rule, value, callback, key) {
           
            if (!isEmpty(value)) {
                let data = this.form.getFieldsValue();
                let total = 0;
                if (key == "cash_paid") {
                    const cardPaid = !isEmpty(data.card_paid)
                        ? data.card_paid
                        : 0;
                    total = value + cardPaid;
                } else {
                    const cashPaid = !isEmpty(data.cash_paid)
                        ? data.cash_paid
                        : 0;
                    total = value + cashPaid;
                }
                let prices = this.getMin();
                if (prices > total) {
                    callback(
                        "Please add value greater than sub total  $" + prices
                    );
                }
            }
            callback();
        },

        getAmount(e) {
            let total = this.summary.subTotal;
            let data = this.form.getFieldsValue();
            const cardPaid = !isEmpty(data.card_paid) ? data.card_paid : 0;
            let cash = e + cardPaid;

            let cashBack = cash - total;
            if (cashBack > 0) {
                this.$emit("cashBack", cashBack);
            } else {
                this.$emit("cashBack", 0);
            }
        }
    }
};
</script>
