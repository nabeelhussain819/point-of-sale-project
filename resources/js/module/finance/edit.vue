<template>
    <div>
        <Invoice :finance="finance" />
        <br />
        <a-card title="Schedules">
            <a-table
                :pagination="false"
                :columns="schedulesColumns"
                :data-source="finance.releated_schedules"
            >
                <a slot="name" slot-scope="text">{{ text }}</a>
            </a-table>
        </a-card>
        <a-card v-if="showPayable()" class="no-print" title="Payable">
            <a-form layout="inline" :form="form" @submit="handleSubmit">
                <a-form-item>
                    <a-input v-decorator="['comment']" placeholder="Comments">
                    </a-input>
                </a-form-item>
                <a-form-item>
                    <a-input
                        :max="finance.total"
                        type="number"
                        v-decorator="[
                            'received_amount',
                            {
                                rules: [
                                    {
                                        required: true,
                                        message:
                                            'Please input your received amount!'
                                    }
                                ]
                            }
                        ]"
                        placeholder="Received Amount $"
                    >
                    </a-input>
                </a-form-item>
                <a-form-item :wrapper-col="{ span: 12, offset: 5 }">
                    <a-button type="primary" html-type="submit">
                        Submit
                    </a-button>
                </a-form-item>
            </a-form>
        </a-card>
        <a-card class="no-print">
            <a-button @click="print" class="no-print float-right" type="primary"
                >Print
            </a-button>
        </a-card>
    </div>
</template>
<script>
const schedulesColumns = [
    {
        title: "Date Of Payment",
        dataIndex: "date_of_payment",
        key: "date_of_payment"
    },
    {
        title: "Paid Amount",
        dataIndex: "received_amount",
        key: "received_amount"
    },
    {
        title: "Comments",
        dataIndex: "comment",
        key: "comment"
    }
];
import Invoice from "./Invoice";
import FinanceService from "../../services/API/FinanceService";
import { notification } from "../../services/helpers";
export default {
    components: { Invoice },
    data() {
        return {
            form: this.$form.createForm(this, { name: "addCustomer" }),
            stateFinance: {},
            schedulesData: [],
            schedulesColumns
        };
    },
    props: {
        finance: {
            default: () => {}
        }
    },
    mounted() {
        this.stateFinance = this.finance;
    },
    methods: {
        showPayable() {
            return this.finance.payable <= 0;
        },
        print() {
            window.print();
        },
        handleSubmit(e) {
            e.preventDefault();
            this.form.validateFields((err, values) => {
                if (!err) {
                    this.payInstallment(values);
                }
            });
        },
        payInstallment(values) {
            FinanceService.payInstallment(this.finance.id, values).then(
                response => {
                    notification(this, response.message);
                    this.$emit("setUpdateFinance", response.finance);
                    this.form.resetFields();
                }
            );
        }
    }
};
</script>

<style scoped>
@media print {
    .ant-modal-header {
        display: none !important;
    }
}
</style>
