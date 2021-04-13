<template>
  <a-row class="sales-header" :gutter="24">
    <a-col :span="10">
      <a-divider orientation="left"> Product & Service </a-divider>
    </a-col>
    <a-col :span="2"> </a-col>
    <a-col :span="12">
      <a-divider orientation="left"> Customer </a-divider>
    </a-col>
    <a-col :span="12"> <add-product /></a-col>

    <a-col :span="12"><customer @getCustomer="getCustomer" /></a-col>
  </a-row>
</template>
<script>
import customer from "./customer";
import AddProduct from "../product/add";
import InventoryService from "../../services/API/InventoryService";
import { isEmpty } from "../../services/helpers";
import { EVENT_CUSTOMERSALE_PRODUCT_ADD } from "../../services/constants";

export default {
  data() {
    return {
      fetchProductsErrors: {},
      customer: null,
    };
  },
  components: {
    customer,
    AddProduct,
  },
  methods: {
    getCustomer(customer) {
      this.customer = customer;
    },
    getProductById(e) {
      this.resetValidation();
      let productId = e.target.value;
      InventoryService.products({ product_id: productId })
        .then((inventory) => {
          this.noProductFound(inventory);
          if (!isEmpty(inventory)) {
            this.$eventBus.$emit(
              EVENT_CUSTOMERSALE_PRODUCT_ADD,
              inventory.product
            );
          }
        }).catch((ex) => {
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
    emitProductDetail() {},
  },
  beforeDestroy() {
    window.removeEventListener(EVENT_CUSTOMERSALE_PRODUCT_ADD, "asd");
  },
};
</script>
