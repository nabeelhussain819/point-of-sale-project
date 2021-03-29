<template>
  <div>
    <a-button v-on:click="show(true)" type="primary">Add</a-button>
    <a-modal
      :visible="visible"
      @cancel="show(false)"
      :footer="null"
      width="98%"
      title="Add Finance"
    >
      <a-row :gutter="24">
        <!-- ================ Customer Detail=========== -->
        <a-col :span="8">
          <a-divider>Customer Detail</a-divider>
          <a-form-item> <customer-lookup /></a-form-item>
        </a-col>
        <!-- ================ Customer Detail=========== -->

        <!-- ================ Product Detail=========== -->
        <a-col :span="12" :offset="4">
          <a-divider class="no-print">Products Detail</a-divider>
          <span class="no-print"> <add-product /></span>
          <span v-if="!isEmpty(product)">
            <a-divider>Selected Products Detail</a-divider
            ><strong>{{ product.name }} , ${{ product.retail_price }}</strong></span
          >
        </a-col>
        <!-- ================ Product Detail=========== -->
      </a-row>
      <a-form
        :form="form"
        :label-col="{ span: 24 }"
        :wrapper-col="{ span: 24 }"
        @submit="handleSubmit"
      >
        <a-row :gutter="24">
          <a-col :span="24">
            <a-divider>Finance Detail</a-divider>
            <!-- ------------------- Finance form ----------------------- -->
            <a-col :span="4">
              <a-form-item label="Type">
                <a-select
                  v-decorator="[
                    `status`,
                    {
                      rules: [{ required: true, message: 'Please insert status!' }],
                    },
                  ]"
                  placeholder="Select a option and change input text above"
                >
                  <a-select-option v-for="status in statuses" :key="status.id">
                    {{ status.name }}</a-select-option
                  >
                </a-select>
              </a-form-item>
            </a-col>
            <a-col :span="3">
              <a-form-item
                label="Total"
                :validate-status="totalErros.validateStatus"
                :help="totalErros.errorMsg"
              >
                <a-input-number
                  :min="1"
                  type="number"
                  v-decorator="[
                    'total',
                    {
                      rules: [{ required: true, message: 'Please insert Total amount!' }],
                    },
                  ]"
                />
              </a-form-item>
            </a-col>
            <a-col :span="3">
              <a-form-item label="Advance">
                <a-input-number
                  type="number"
                  :min="1"
                  v-decorator="[
                    'advance',
                    {
                      rules: [{ required: true, message: 'Please insert advance !' }],
                    },
                  ]"
                />
              </a-form-item>
            </a-col>
            <a-col :span="4">
              <a-form-item label="Duration">
                <a-input
                  type="number"
                  addon-before="Month"
                  :min="1"
                  v-decorator="[
                    'duration_period',
                    {
                      rules: [{ required: true, message: 'Please insert duration!' }],
                    },
                  ]"
                />
              </a-form-item>
            </a-col>
            <a-col :span="5">
              <a-form-item label="Start Date"
                ><a-date-picker
                  v-decorator="[
                    'start_date',
                    {
                      rules: [],
                      initialValue: installments.start_date,
                    },
                  ]"
                />
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
              <a-form-item
                class="no-print"
                :validate-status="finalErrors.validateStatus"
                :help="finalErrors.errorMsg"
                label="Actions"
              >
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
          <a-col :span="4"><strong>Amount</strong></a-col>
          <a-col :span="4"><strong>Status</strong></a-col>
        </a-row>
        <a-row :gutter="16" v-for="(installment, key) in installments" :key="key">
          <a-col :span="4"
            ><a-form-item><a-date-picker
              v-decorator="[
                `installmentItem[${key}][date_of_payment]`,
                {
                  rules: [],
                  initialValue: moment(installment.date_of_payment, dateFormat),
                },
              ]"
            
          /></a-form-item></a-col>
          <a-col :span="4"
            ><a-form-item><a-date-picker
              v-decorator="[
                `installmentItem[${key}][due_date]`,
                {
                  rules: [],
                  initialValue: moment(installment.date_of_payment, dateFormat),
                },
              ]"
           
          /></a-form-item></a-col>
          <a-col :span="4">
           <a-form-item> <a-input
              disabled
              type="number"
              :min="1"
             
              v-decorator="[
                `installmentItem[${key}][amount]`,
                {
                  initialValue: installment.amount,
                  rules: [{ required: true, message: 'Please insert advance !' }],
                },
              ]"
          /></a-form-item></a-col>
          <a-col :span="4">
           <a-form-item> <a-select
              :disabled="!isCreated"
          
              v-decorator="[
                `installmentItem[${key}][status]`,
                {
                  initialValue: !isCreated ? 1 : installment.status,
                  rules: [{ required: true, message: 'Please insert status!' }],
                },
              ]"
              placeholder="Select a option and change input text above"
            >
              <a-select-option v-for="status in installmentStatus" :key="status.id">
                {{ status.name }}</a-select-option
              >
            </a-select></a-form-item></a-col
          >
          <a-col :span="24"> <br /></a-col> <br />
        </a-row>
        <a-row v-if="!isEmpty(installments)">
          <a-form-item
            class="no-print"
            :validate-status="finalErrors.validateStatus"
            :help="finalErrors.errorMsg"
            label="Actions"
          >
            <a-button @click="print" type="default">Print </a-button>
            <a-button @click="handleInstallments" type="primary">Settle </a-button>
          </a-form-item></a-row
        >
      </a-form>
    </a-modal>
  </div>
</template>
<script>
import CustomerLookup from "../customer/lookup";
import AddProduct from "../product/add";
import moment from "moment";
import { isEmpty } from "../../services/helpers";
import {
  EVENT_CUSTOMERSALE_PRODUCT_ADD,
  EVENT_CUSTOMERSALE_CUSTOMER_DETAIL,
} from "../../services/constants";
export default {
  components: { CustomerLookup, AddProduct },
  data() {
    this.dateFormat = "YYYY-MM-DD";
    return {
      visible: false,
      formLayout: "horizontal",
      form: this.$form.createForm(this, { name: "addFinance" }),
      isCreated: false,
      statuses: [
        { id: 1, name: "Layaway" },
        { id: 2, name: "In Store Finance" },
      ],
      installmentStatus: [
        { id: 1, name: "Pending" },
        { id: 2, name: "Payment Due" },
        { id: 3, name: "Completed" },
        { id: 4, name: "Cancelled/ Void" },
      ],
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
  },
  methods: {
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
      console.log("han bhaikesa dia ", values);
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
      if (isEmpty(this.product) && isEmpty(this.customer)) {
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
