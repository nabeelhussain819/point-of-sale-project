<template>
  <div>
    <a-col :span="6">
      <a-form-item
        label="Lookup"
        :validate-status="fetchProductsErrors.validateStatus"
        :help="fetchProductsErrors.errorMsg"
      >
        <a-input-search
          enter-button
          v-decorator="[
            'product_upc',
            {
              rules: [{ required: true, message: 'Please insert keys!' }],
            },
          ]"
          @search="getProductById"
          type="text" /></a-form-item
    ></a-col>
    <a-col :span="6">
      <a-form-item label="Product Name">
        <a-input :disabled="true" v-decorator="['product_name']" type="customer_number" />
        <a-input
          :disabled="true"
          v-decorator="['product_id']"
          type="hidden"
        /> </a-form-item
    ></a-col>
    <a-col :span="6">
      <a-form-item label="Product Serial Number">
        <a-input
          v-decorator="[
            'serial_number',
            {
              rules: [{ required: true, message: 'Please insert serial!' }],
            },
          ]" /></a-form-item
    ></a-col>

    <a-col :span="6">
      <a-form-item label="Comments">
        <a-textarea :rows="1" v-decorator="['comments']" /></a-form-item
    ></a-col>
  </div>
</template>
<script>
import InventoryService from "../../services/API/InventoryService";
import { isEmpty } from "../../services/helpers";
export default {
  props: {
    form: {
      default: () => {},
    },
  },
  data() {
    return {
      fetchProductsErrors: {},
      customer: null,
    };
  },
  methods: {
    getProductById(e) {
      this.resetValidation();
      let productId = e;
      InventoryService.products({ product_id: productId, OrUPC: productId })
        .then((inventory) => {
          this.noProductFound(inventory);
          if (!isEmpty(inventory)) {
            this.$emit("getProduct", inventory);
            this.form.setFieldsValue({
              product_name: inventory.product.name,
              product_id: inventory.product.id,
              serial_number:inventory.serial_number
            });
          }
        })
        .catch((ex) => {
          if (ex.response.status === 409) {
            this.fetchProductsErrors = {
              validateStatus: "error",
              errorMsg: ex.response.data.message,
            };
          }
          if (ex.response.status === 404) {
            this.fetchProductsErrors = {
              validateStatus: "error",
              errorMsg: "No Product Found Against Key",
            };
          }
        });
    },
    noProductFound(data) {
      if (isEmpty(data)) {
        this.fetchProductsErrors = {
          validateStatus: "error",
          errorMsg: "No Product Found Against Key",
        };
      }
    },
    resetValidation() {
      this.fetchProductsErrors = {};
    },
  },
};
</script>

