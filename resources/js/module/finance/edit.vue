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
        <a-card v-if="showPayable" class="no-print" title="Payable">
            <a-form layout="inline" :form="form" @submit="handleSubmit">
                <a-form-item>
                    <a-input v-decorator="['comment']" placeholder="Comments">
                    </a-input>
                </a-form-item>
                <a-form-item>
                    <a-input-number
                        :max="finance.payable"
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
                    </a-input-number>
                </a-form-item>
                <a-form-item :wrapper-col="{ span: 12, offset: 5 }">
                    <a-button type="primary" html-type="submit">
                        Submit
                    </a-button>
                </a-form-item>
            </a-form>
        </a-card>
        <a-card>
            <a-row>
                <a-col :span="12">
                    <img
                        class="attachment"
                        v-if="!isEmpty(getImages())"
                        :src="getImages()"
                    />
                </a-col>
                <a-col :span="12">
                    <a-button
                        @click="print"
                        class="no-print float-right"
                        type="primary"
                        >Print
                    </a-button>

                    <a-select
                        :loading="statusLoader"
                        :default-value="finance.status_id"
                        class="no-print float-right"
                        style="width:250px; margin-right:10px"
                        @change="value => statusChange(value)"
                        placeholder="Select a option and change input text above"
                    >
                        <a-select-option
                            v-for="status in installmentStatus"
                            :key="status.id"
                        >
                            {{ status.name }}</a-select-option
                        >
                    </a-select>
                </a-col>
            </a-row>
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
import { isEmpty, notification } from "../../services/helpers";
import { FINANCE_INSTALLMENT_STATUS } from "../../services/constants";
export default {
    components: { Invoice },
    data() {
        return {
            statusLoader: false,
            installmentStatus: FINANCE_INSTALLMENT_STATUS,
            form: this.$form.createForm(this, { name: "addCustomer" }),
            stateFinance: {},
            schedulesData: [],
            schedulesColumns,
            isEmpty
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
    computed: {
        showPayable() {
            return parseFloat(this.finance.payable) > 0;
        }
    },
    methods: {
        getImages() {
            if (!isEmpty(this.finance.attachmentTemp)) {
                console.log(this.finance.attachmentTemp[0]);
                return this.finance.attachmentTemp[0];
            }
        },
        statusChange(status_id) {
            this.statusLoader = true;
            FinanceService.update(this.finance.id, { status_id })
                .then(response => {
                    notification(this, response.message);
                })
                .catch(error => errorNotification(this, error))
                .finally(() => (this.statusLoader = false));
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
            FinanceService.payInstallment(this.finance.id, values)
                .then(response => {
                    notification(this, response.message);
                    this.$emit("setUpdateFinance", response.finance);
                    this.form.resetFields();
                })
                .catch(error => errorNotification(this, error));
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
.attachment {
    max-width: 300px;
}
</style>
