<template>
  <div>
    <a-card title="Quick Sale">
      <a-form @submit="handleSubmit">
        <a-form-item></a-form-item>

        <AddProduct :rules="rules" />
        <a-button :disabled="disabled" htmlType="submit" type="primary"> Sale </a-button>
      </a-form>
    </a-card>
  </div>
</template>
<script>
import AddProduct from "../product/add";
import { EVENT_CUSTOMERSALE_PRODUCT_ADD } from "../../services/constants";
export default {
  data() {
    return {
      disabled: true,
      formLayout: "horizontal",
      form: this.$form.createForm(this, { name: "addRepair" }),
      rules: [{ required: true, message: "Please input your customer number!" }],
      product: {},
    };
  },
  components: {
    AddProduct,
  },
  mounted() {
    let setProducts = this.updatedProducts;
    this.$eventBus.$on(EVENT_CUSTOMERSALE_PRODUCT_ADD, function (product) {
      setProducts(product);
    });
  },
  methods: {
    updatedProducts(product) {
      this.disabled = false;
      this.product = product;
    },
    handleSubmit(e) {
      e.preventDefault();
      this.form.validateFields((err, values) => {
        if (!err) {
          window.location.href = `/sales/create?OrUPC=${this.product.id}`;
        }
      });
    },
  },
};
</script>
