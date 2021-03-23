<template>
  <a-card hoverable>
    <div v-if="!isCreated">
      <a-button slot="extra" @click="showModal(true)" icon="user-add" type="primary"
        >Add Customer</a-button
      >
    </div>
    <span v-else>
      <a-button slot="extra" type="link">edit</a-button>
      <div slot="title">
        <span> Naveed Nasib</span> <br /><a-icon type="phone" /> 03323606922
      </div>
    </span>
    <a-modal v-model="visible" title="Associate Customer ">
      <a-form
        :form="form"
        :label-col="{ span: 24 }"
        :wrapper-col="{ span: 24 }"
        @submit="handleSubmit"
      >
        <a-col :span="24">
          <a-form-item label="Customer Name">
            <a-auto-complete
              v-decorator="[
                'name',
                {
                  initialValue: selectedCustomer.name,
                  rules: [
                    { required: true, message: 'Please input your customer_number!' },
                  ],
                },
              ]"
              v-model="value"
              :data-source="customers"
              style="width: 200px"
              placeholder="input here"
              @select="handleChange"
              @search="customerSearch"
              @change="handleChange"
            /> </a-form-item
        ></a-col>
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
import { filterOption } from "../../services/helpers";
import CustomerService from "../../services/API/CustomerService";
export default {
  data() {
    return {
      formLayout: "horizontal",
      form: this.$form.createForm(this, { name: "addCustomer" }),
      isCreated: false,
      visible: false,
      customers: [],
      filterOption,
      selectedCustomer: [],
    };
  },
  methods: {
    showModal(show) {
      this.visible = show;
    },
    handleChange() {},
    handleSubmit() {},
    customerSearch(searchText, event) {
      this.customers = !searchText ? [] : searchText;
      // if (value.length > 3) {
      //   this.customerSearchLoading = true;
      //   CustomerService.search({ search: value }).then((customers) => {
      //     this.customers = customers.data;
      //   });
      //   this.customerSearchLoading = false;
      // }
    },
  },
};
</script>
