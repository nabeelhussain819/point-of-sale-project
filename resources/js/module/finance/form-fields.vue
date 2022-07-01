<template>
    <a-skeleton :loading="loading">
        <a-row :gutter="24">
            <!-- ================ Customer Detail=========== -->
            <a-col :span="8">
                <a-divider>Customer Detail</a-divider>
                <a-form-item>
                    <customer-lookup />
                </a-form-item>
            </a-col>
            <!-- ================ Customer Detail=========== -->

            <!-- ================ Product Detail=========== -->
            <a-col :span="12" :offset="4">
                <a-divider class="no-print">Products Detail</a-divider>
                <span class="no-print">
                    <add-product />
                </span>
                <span v-if="!isEmpty(product)">
                    <a-divider>Selected Products Detail</a-divider><strong>{{ product.name }} , ${{ product.retail_price
                    }}</strong>
                </span>
            </a-col>
            <!-- ================ Product Detail=========== -->
        </a-row>
        <a-form :form="form" :label-col="{ span: 24 }" :wrapper-col="{ span: 24 }" @submit="handleSubmit">
            <a-row :gutter="24">
                <a-col :span="24">
                    <a-divider>Finance Detail</a-divider>
                    <!-- ------------------- Finance form ----------------------- -->
                    <a-col :span="4">
                        <a-form-item label="Type">
                            <a-select v-decorator="[
                                `type`,
                                {
                                    rules: [{ required: true, message: 'Please insert status!' }],
                                    initialValue: finance.type,
                                },
                            ]" placeholder="Select a option and change input text above">
                                <a-select-option v-for="type in types" :key="type.id">
                                    {{ type.name }}</a-select-option>
                            </a-select>
                        </a-form-item>
                    </a-col>
                    <a-col :span="3">
                        <a-form-item label="Total" :validate-status="totalErros.validateStatus"
                            :help="totalErros.errorMsg">
                            <a-input-number :min="1" type="number" v-decorator="[
                                'total',
                                {
                                    initialValue: finance.total,
                                    rules: [{ required: true, message: 'Please insert Total amount!' }],
                                },
                            ]" />
                        </a-form-item>
                    </a-col>
                    <a-col :span="3">
                        <a-form-item label="Advance">
                            <a-input-number type="number" :min="1" v-decorator="[
                                'advance',
                                {
                                    initialValue: finance.advance,
                                    rules: [{ required: true, message: 'Please insert advance !' }],
                                },
                            ]" />
                        </a-form-item>
                    </a-col>
                    <a-col :span="4">
                        <a-form-item label="Duration">
                            <a-input type="number" addon-before="Month" :min="1" v-decorator="[
                                'duration_period',
                                {
                                    initialValue: finance.duration_period,
                                    rules: [{ required: true, message: 'Please insert duration!' }],
                                },
                            ]" />
                        </a-form-item>
                    </a-col>
                    <a-col :span="5">
                        <a-form-item label="Start Date">
                            <a-date-picker v-decorator="[
                                'start_date',
                                {
                                    rules: [],
                                    initialValue: finance.start_date,
                                },
                            ]" />
                        </a-form-item>
                    </a-col>
                    <a-col :span="3">
                        <a-form-item>
                            <span slot="label">
                                Payable
                                <a-tooltip title="Payable each month">
                                    <a-icon type="question-circle-o" />
                                </a-tooltip>
                            </span>
                            {{ eachMonthPayable }}
                        </a-form-item>
                    </a-col>
                    <a-col :span="2">
                        <a-form-item class="no-print" :validate-status="finalErrors.validateStatus"
                            :help="finalErrors.errorMsg" label="Actions">
                            <a-button html-type="submit" type="primary">Schedule </a-button>
                        </a-form-item>
                    </a-col>
                    <!-- ------------------- Finance form ----------------------- -->
                </a-col>
            </a-row>

            <a-divider>Installments</a-divider>

            <a-row v-if="!isEmpty(installments)">
                <a-col :span="4"><strong>Date of payment</strong></a-col>
                <a-col :span="4"><strong>Due Date</strong></a-col>
                <a-col :span="2"><strong>Amount</strong></a-col>
                <a-col :span="4"><strong>Status</strong></a-col>
                <a-col v-if="isCreated" :span="3"><strong>Recived At</strong></a-col>
                <a-col v-if="isCreated" :span="3"><strong>Recived Amount</strong></a-col>
            </a-row>
            <a-row :gutter="16" v-for="(installment, key) in installments" :key="key">
                <a-col :span="4">
                    <a-form-item>
                        <a-date-picker :disabled="isCreated" v-decorator="[
                            `installmentItem[${key}][date_of_payment]`,
                            {
                                rules: [],
                                initialValue: moment(installment.date_of_payment, dateFormat),
                            },
                        ]" />
                    </a-form-item>
                </a-col>
                <a-col :span="4">
                    <a-form-item>
                        <a-date-picker :disabled="isCreated" v-decorator="[
                            `installmentItem[${key}][due_date]`,
                            {
                                rules: [],
                                initialValue: moment(installment.date_of_payment, dateFormat),
                            },
                        ]" />
                    </a-form-item>
                </a-col>
                <a-col :span="2">
                    <a-form-item>
                        <a-input type="hidden" v-decorator="[
                            `installmentItem[${key}][id]`,
                            {
                                initialValue: installment.id,
                            },
                        ]" />
                        <a-input disabled type="number" :min="1" v-decorator="[
                            `installmentItem[${key}][amount]`,
                            {
                                initialValue: installment.amount,
                                rules: [{ required: true, message: 'Please insert advance !' }],
                            },
                        ]" />
                    </a-form-item>
                </a-col>
                <a-col :span="4">
                    <a-form-item>
                        <a-select :disabled="!isCreated" v-decorator="[
                            `installmentItem[${key}][status]`,
                            {
                                initialValue: !isCreated ? '1' : installment.status,
                                rules: [{ required: true, message: 'Please insert status!' }],
                            },
                        ]" placeholder="Select a option and change input text above">
                            <a-select-option v-for="status in installmentStatus" :key="status.id.toString()">
                                {{ status.name }}</a-select-option>
                        </a-select>
                    </a-form-item>
                </a-col>

                <!-- After Update input fields -->
                <a-col :span="3" v-if="isCreated">
                    <a-form-item>
                        <a-date-picker v-decorator="[
                            `installmentItem[${key}][received_date]`,
                            {
                                rules: [],
                                initialValue:
                                    installment.received_date &&
                                    moment(installment.received_date, dateFormat),
                            },
                        ]" />
                    </a-form-item>
                </a-col>

                <a-col :span="3" v-if="isCreated">
                    <a-form-item>
                        <a-input type="number" :min="1" v-decorator="[
                            `installmentItem[${key}][received_amount]`,
                            {
                                initialValue: installment.received_amount,
                                rules: [{ required: true, message: 'Please insert received amount !' }],
                            },
                        ]" />
                    </a-form-item>
                </a-col>
                <a-col :span="3" v-if="isCreated">
                    <a-button :disabled="!isEmpty(installment.received_amount)" @click="submitInstallment(key)"
                        type="primary">{{ !isEmpty(installment.received_amount) ? "Paid" : "Pay" }}
                    </a-button>
                </a-col>
                <!-- After Update input fields -->
            </a-row>
            <a-row v-if="!isEmpty(installments)">
                <a-col>
                    <a-form-item class="no-print" :validate-status="finalErrors.validateStatus"
                        :help="finalErrors.errorMsg" label="Actions">
                        <a-button @click="print" type="default">Print </a-button>
                        <span></span>
                        <a-button v-if="!isCreated" @click="handleInstallments" type="primary">Create
                        </a-button>
                    </a-form-item>
                </a-col>
            </a-row>
        </a-form>
    </a-skeleton>
</template>
<script>
import CustomerLookup from "../customer/lookup";
import AddProduct from "../product/add";
import moment from "moment";
import { isEmpty, notification } from "../../services/helpers";
import FinanceService from "../../services/API/FinanceService";
import {
    EVENT_CUSTOMERSALE_PRODUCT_ADD,
    EVENT_CUSTOMERSALE_CUSTOMER_DETAIL,
    FINANCE_TYPE,
    FINANCE_INSTALLMENT_STATUS,
    EVENT_FINANCE_ADD_RECORD,
} from "../../services/constants";
export default {
    props: { finance: { default: () => { } }, isCreated: { default: false } },
    components: { CustomerLookup, AddProduct },
    data() {
        this.dateFormat = "YYYY-MM-DD";
        return {
            loading: false,
            visible: false,
            formLayout: "horizontal",
            form: this.$form.createForm(this, { name: "addFinance" }),
            types: FINANCE_TYPE,
            installmentStatus: FINANCE_INSTALLMENT_STATUS,
            eachMonthPayable: 0,
            totalErros: {},
            finalErrors: {},
            uuid: 0,
            uuidString: "uuid-",
            installments: {},
            isEmpty,
            product: null,
            customer: null,
        };
    },
    mounted() {
        let setProduct = this.setProduct;
        let setCustomer = this.setCustomer;
        this.$eventBus.$on(EVENT_CUSTOMERSALE_PRODUCT_ADD, function (product) {
            setProduct(product);
        });
        this.$eventBus.$on(EVENT_CUSTOMERSALE_CUSTOMER_DETAIL, function (customer) {
            setCustomer(customer);
        });
        this.showUpdateFinanceData();
    },
    methods: {
        submitInstallment(key) {
            let fields = this.form.getFieldsValue();

            ///valdaition of not null amount and date
            let fieldsValue = fields.installmentItem[key];
            FinanceService.updateInstallment(this.finance.id, fieldsValue).then((finance) => {
                this.installments = finance.releated_schedules;
                notification(this, "Insallment has been received and Updated");
            });
        },
        showUpdateFinanceData() {
            let finance = this.finance;
            if (!isEmpty(finance)) {
                FinanceService.get(finance.id).then((finance) => {
                    this.installments = finance.releated_schedules;
                });
            }
        },
        setProduct(product) {
            this.product = product;
        },
        setCustomer(customer) {
            this.customer = customer;
        },
        print() {
            window.print();
        },
        getUid() {
            this.uuid = this.uuid + 1;
            return this.uuidString + this.uuid;
        },
        moment,
        show(show) {
            this.$emit("onClose", show);
            this.visible = show;
        },
        validated(e, callback) {
            e.preventDefault();
            this.form.validateFields((err, values) => {
                if (this.validation(values)) {
                    return false;
                }
                this.resetValidation();
                if (!err) {
                    callback(values);
                }
            });
        },
        handleInstallments(e) {
            this.validated(e, this.create);
        },
        create(values) {
            this.loading = true;
            FinanceService.create({
                ...values,
                product_id: this.product.id,
                customer_id: this.customer.id,
            })
                .then((response) => {
                    this.$eventBus.$emit(EVENT_FINANCE_ADD_RECORD, true);
                    notification(this, response.message);
                    this.show(false);
                })
                .finally(() => this.loading);
        },
        handleSubmit(e) {
            e.preventDefault();
            this.validated(e, this.createInstalments);
        },
        createInstalments(values) {
            let instalmentAmount = this.getInstallmentAmount(values);
            let installments = {};
            this.handleInsatalmentsDates(values);
            let startDate = values.start_date.format(this.dateFormat);
            startDate = moment(startDate, this.dateFormat);

            for (let i = 0; i < values.duration_period; i++) {
                let uuid = this.getUid();
                let month = startDate.format(this.dateFormat);
                month = moment(month, this.dateFormat)
                    .add(i + 1, "M")
                    .format(this.dateFormat);

                installments[uuid] = {
                    amount: instalmentAmount,
                    date_of_payment: moment(month, this.dateFormat).format(this.dateFormat),
                    due_date: moment(month, this.dateFormat)
                        .add(10, "days")
                        .format(this.dateFormat),
                };
            }
            this.installments = installments;
        },
        getInstallmentAmount(values) {
            let duration = values.duration_period;
            let remaining = values.total - values.advance;

            return remaining / duration;
        },
        handleInsatalmentsDates(values) {
            if (isEmpty(values.start_date)) {
            }
        },
        validation(values) {
            if (values.total < values.advance) {
                this.totalErros = {
                    validateStatus: "error",
                    errorMsg: "Total not less than advance",
                };
                return true;
            }
            if (isEmpty(this.product) || isEmpty(this.customer)) {
                this.finalErrors = {
                    validateStatus: "error",
                    errorMsg: "Customer Or Product not selected",
                };
                return true;
            }
            return false;
        },
        resetValidation() {
            this.totalErros = {};
            this.finalErrors = {};
        },
    },
};
</script>

<style>
@media print {

    .no-print,
    .no-print * {
        display: none !important;
    }
}
</style>
