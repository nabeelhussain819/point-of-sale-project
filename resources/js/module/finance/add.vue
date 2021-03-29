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
      <a-form
        :form="form"
        :label-col="{ span: 24 }"
        :wrapper-col="{ span: 24 }"
        @submit="handleSubmit"
      >
        <a-row :gutter="24">
          <a-col :span="4">
            <a-divider>Customer Detail</a-divider>
            <a-form-item> <customer-lookup /></a-form-item>
          </a-col>
          <a-col :span="20">
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
              <a-form-item label="Actions">
                <a-button html-type="submit" type="primary">Schedule </a-button>
              </a-form-item>
            </a-col>
            <!-- ------------------- Finance form ----------------------- -->
          </a-col>
        </a-row>

        <a-divider>Installments</a-divider>
        <a-row v-for="(installment, key) in installments" :key="key">
          <a-col :span="4"
            ><a-date-picker
              v-decorator="[
                `installmentItem[${key}][date_of_payment]`,
                {
                  rules: [],
                  initialValue: moment(installment.date_of_payment, dateFormat),
                },
              ]"
              :default-value="moment(installment.date_of_payment, dateFormat)"
              disabled
          /></a-col>
          <a-col :span="4"
            ><a-date-picker
              v-decorator="[
                `installmentItem[${key}][due_date]`,
                {
                  rules: [],
                  initialValue: moment(installment.date_of_payment, dateFormat),
                },
              ]"
              :default-value="moment(installment.due_date, dateFormat)"
              disabled
          /></a-col>
          <a-col :span="4">
            <a-input
              type="number"
              :min="1"
              :default-value="installment.amount"
              disabled
              v-decorator="[
                `installmentItem[${key}][amount]`,
                {
                  initialValue: installment.amount,
                  rules: [{ required: true, message: 'Please insert advance !' }],
                },
              ]"
          /></a-col>
          <a-col :span="4">
            <a-select
              disabled
              :default-value="!isCreated ? 1 : installment.status"
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
            </a-select></a-col
          >
        </a-row>
      </a-form>
    </a-modal>
  </div>
</template>
<script>
import CustomerLookup from "../customer/lookup";
import moment from "moment";
import { isEmpty } from "../../services/helpers";
export default {
  components: { CustomerLookup },
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
      uuid: 0,
      uuidString: "uuid-",
      installments: {},
    };
  },
  methods: {
    getUid() {
      this.uuid = this.uuid + 1;
      return this.uuidString + this.uuid;
    },
    moment,
    show(show) {
      this.visible = show;
    },
    handleSubmit(e) {
      e.preventDefault();
      this.form.validateFields((err, values) => {
        if (this.validation(values)) {
          return false;
        }
        if (!err) {
          this.createInstalments(values);

          // this.isCreated ? this.update(values) : this.save(values);
        }
      });
    },
    createInstalments(values) {
      let instalmentAmount = this.getInstallmentAmount(values);
      let installments = {};
      this.handleInsatalmentsDates(values);
      let startDate = values.start_date.format(this.dateFormat);
      startDate = moment(startDate, this.dateFormat);
      console.log(
        moment("2021-04-20", this.dateFormat).add(1, "M").format(this.dateFormat)
      );
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
      console.log("installments", installments);
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
      return false;
    },
  },
};
</script>
