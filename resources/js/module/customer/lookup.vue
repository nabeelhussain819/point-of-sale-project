<template>
  <a-card hoverable :bordered="false">
    <div v-if="!isCreated()">
      <a-button slot="extra" @click="showModal(true)" icon="user-add" type="primary"
        >Add Customer</a-button
      >
    </div>
    <span v-else>
      <a-button slot="extra" @click="showModal(true)" type="link">edit</a-button>
      <div slot="title">
        {{ currentCustomer.name_phone }}
      </div>
    </span>
    <a-modal v-model="visible" title="Associate Customer">
      <template slot="footer">
        <a-button key="back" @click="showModal(false)"> Cancel </a-button>
        <a-button
          key="submit"
          type="primary"
          :loading="customerSearchLoading"
          @click="handleSubmit"
        >
          Add
        </a-button>
      </template>
      <a-form
        :form="form"
        :label-col="{ span: 24 }"
        :wrapper-col="{ span: 24 }"
        @submit="handleSubmit"
      >
        <a-col :span="24">
          <a-form-item label="Customer Name">
            <!-- ------------------- -->

            <a-select
              mode="tags"
              :filter-option="filterOption"
              :maxTagCount="maxCustomer"
              @select="onCustomerSelect"
              @search="customerSearch"
              :loading="customerSearchLoading"
              v-decorator="[
                'name',
                {
                  initialValue: selectedCustomer.name,
                  rules: [
                    { required: true, message: 'Please input your customer number!' },
                  ],
                },
              ]"
            >
              <a-select-option
                v-for="customer in customers"
                :key="customer.id.toString()"
                :customer="customer"
              >
                {{ customer.name_phone }}
              </a-select-option>
            </a-select>
            <!-- ------------------------------ -->
          </a-form-item></a-col
        >
        <a-col :span="24">
          <a-form-item label="Customer Phone">
            <a-input
              type="number"
              v-decorator="[
                'phone',
                {
                  initialValue: selectedCustomer.phone,
                  rules: [
                    { required: true, message: 'Please input your customer_number!' },
                  ],
                },
              ]" /></a-form-item
        ></a-col>
      </a-form>
    </a-modal>
  </a-card>
</template>
<script>
import { filterOption, isEmpty, errorNotification } from "../../services/helpers";
import CustomerService from "../../services/API/CustomerService";
import { EVENT_CUSTOMERSALE_CUSTOMER_DETAIL } from "../../services/constants";
export default {
  data() {
    return {
      formLayout: "horizontal",
      form: this.$form.createForm(this, { name: "addCustomer" }),
      visible: false,
      customers: [],
      filterOption,
      selectedCustomer: [],
      customerSearchLoading: false,
      maxCustomer: 1,
      currentCustomer: null,
    };
  },
  methods: {
    isCreated() {
      return !isEmpty(this.currentCustomer);
    },
    emitCustomerId(customer) {
      this.currentCustomer = customer;
      this.$eventBus.$emit(EVENT_CUSTOMERSALE_CUSTOMER_DETAIL, customer);

      this.showModal(false);
    },
    showModal(show) {
      this.visible = show;
    },
    onCustomerSelect(value, options) {
      // on selectif the current customer is from database
      let selectedCustomer = options.data.attrs.customer;
      if (!isEmpty(selectedCustomer) && !isEmpty(selectedCustomer.phone)) {
        this.currentCustomer = selectedCustomer;
        this.form.setFieldsValue({ phone: selectedCustomer.phone });
      } else {
        this.currentCustomer = null;
        this.form.setFieldsValue({ phone: null });
      }
    },
    handleSubmit(e) {
      e.preventDefault();
      this.form.validateFields((err, values) => {
        if (!err && this.currentCustomer === null) {
          this.customerSearchLoading = true;
          CustomerService.create({ ...values, isApi: true })
            .then((customer) => {
              this.emitCustomerId(customer);
            })
            .catch((err) => errorNotification(this, err));
        }

        if (!isEmpty(this.currentCustomer)) {
          this.emitCustomerId(this.currentCustomer);
        }

        this.customerSearchLoading = false;
      });
    },
    customerSearch(searchText, event) {
      if (!isEmpty(searchText) && searchText.length > 3) {
        this.customerSearchLoading = true;
        CustomerService.search({ search: searchText })
          .then((customers) => {
            this.customers = customers.data;
          })
          .catch((err) => {
            console.log("error", err.response.data);
          })
          .finally(() => {
            this.customerSearchLoading = false;
          });
        this.customerSearchLoading = false;
      }
    },
  },
};
</script>
