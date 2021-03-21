<template>
  <a-row class="sales-header" :gutter="24">
    <a-col :span="10">
      <a-divider orientation="left"> Product & Service </a-divider>
    </a-col>
    <a-col :span="2"> </a-col>
    <a-col :span="12">
      <a-divider orientation="left"> Customer </a-divider>
    </a-col>
    <a-col :span="5">
      <a-form-item
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
    <a-col :span="1"> <a-form-item :colon="false" label="OR">|</a-form-item></a-col>
    <a-col :span="6">
      <a-form-item label="Add Item">
        <a-button type="primary">Add Item </a-button>
      </a-form-item>
    </a-col>

    <a-col :span="12"><customer /></a-col>
  </a-row>
</template>
<script>
import customer from "./customer";
import ProductService from "../../services/API/ProductService";
import { isEmpty } from "../../services/helpers";
import { app } from "../../app";
export default {
  data() {
    return {
      fetchProductsErrors: {},
    };
  },
  components: {
    customer,
  },
  methods: {
    getProductById(e) {
      this.resetValidation();
      let productId = e.target.value;
      ProductService.all({ id: productId }).then((products) => {
        this.noProductFound(products.data);
        if (!isEmpty(products.data) && products.data[0]) {
          this.$eventBus.$emit("PRODUCTEVENT", products.data[0]);
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
    window.removeEventListener("PRODUCTEVENT", "asd");
  },
};
</script>

<style scoped>
.sales-header {
  /* background-color: #cbcdff; */
}
</style>
