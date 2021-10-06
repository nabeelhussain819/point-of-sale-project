<template>
    <div class="no-print">
        <a-row :gutter="2">
            <!-- <a-form :form="form" :label-col="{ span: 24 }" :wrapper-col="{ span: 24 }"> -->
            <a-radio-group
                class="no-print"
                name="radioGroup"
                @change="paymentMode"
                default-value="cash"
            >
                <a-radio value="cash"> Cash </a-radio>
                <a-radio value="card"> Card </a-radio>
            </a-radio-group>
            <br /><br />

            <a-col v-if="!isCash" :span="24">
                <a-form-item label="Card Number">
                    <a-input
                        type="card_number"
                        v-decorator="[
                            'customer_card_number',
                            {
                                rules: [
                                    {
                                        required: true,
                                        message:
                                            'Please input your customer card number!'
                                    }
                                ]
                            }
                        ]"/></a-form-item
            ></a-col>
            <a-col :span="24">
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
                                        required: true,
                                        message: 'Please input your amount!'
                                    },
                                    {
                                        validator: (rule, value, callback) =>
                                            validateTotal(rule, value, callback)
                                    }
                                ]
                            }
                        ]"
                    />
                    <!-- ------------------------------ -->
                </a-form-item></a-col
            >
            <a-col v-if="isCash" :span="24">
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
    props: { summary: { default: () => {} } },
    data() {
        return {
            isCash: true
        };
    },
    methods: {
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
                let prices = this.getMin();
                if (prices > value) {
                    callback(
                        "Please add value greater than sub total  $" + prices
                    );
                }
            }
            callback();
        },
        getAmount(e) {
            let cash = e;
            let total = this.summary.subTotal;
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
