<template>
  <a-row :gutter="16">
    <a-col :span="11">
      <a-form-item
        placeholder="insert id and press enter"
        :validate-status="fetchProductsErrors.validateStatus"
        :help="fetchProductsErrors.errorMsg"
        label="Scan Product Number"
      >
        <a-input
          @pressEnter="getProductById"
          v-decorator="[
            'product_id',
            {
              rules: [],
            },
          ]"
        /> </a-form-item
    ></a-col>
    <!-- <a-col :span="2"> <a-form-item :colon="false" label="OR">|</a-form-item></a-col>
    <a-col :span="11">
      <a-form-item label="Add Item">
        <a-button type="primary">Add Item </a-button>
      </a-form-item>
    </a-col> -->
  </a-row>
</template>
<script>
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
  methods: {
    getProductById(e) {
      this.resetValidation();
      let productId = e.target.value;
      InventoryService.products({ product_id: productId,OrUPC:productId }).then((inventory) => {
        this.noProductFound(inventory.data);
        if (!isEmpty(inventory.data) && inventory.data[0]) {
          this.$eventBus.$emit(EVENT_CUSTOMERSALE_PRODUCT_ADD, inventory.data[0].product);
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
  beforeDestroy() {
    // window.removeEventListener(EVENT_CUSTOMERSALE_PRODUCT_ADD, "asd");
  },
};
</script>
