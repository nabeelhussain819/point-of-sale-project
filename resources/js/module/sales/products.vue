<template>
  <div>
    <a-row :gutter="23">
      <a-col :span="1"><strong>Id</strong></a-col>
      <a-col :span="3"><strong>Name</strong></a-col>

      <a-col :span="4"><strong>Serial</strong></a-col>
      <a-col :span="2"><strong>Quantity</strong></a-col>
      <a-col :span="3"><strong>Discount</strong></a-col>
      <a-col :span="3"><strong>Unit Prices</strong></a-col>
      <a-col :span="3"><strong>Extended Prices</strong></a-col>
      <a-col :span="2"><strong>Total</strong></a-col>
      <a-col :span="1"><strong></strong></a-col>
    </a-row>
    <a-form
      :form="form"
      :label-col="{ span: 24 }"
      :wrapper-col="{ span: 24 }"
      @submit="handleSubmit"
    >
      <a-row v-for="(product, key) in products" :key="key" :gutter="23">
        <a-col :span="1"> {{ product.id }}</a-col>
        <a-col :span="3"> {{ product.name }}</a-col>

        <a-col :span="4">
          <a-form-item>
            <a-input-search
              :disabled="!product.has_serial_number"
              v-decorator="[
                `productItem[${key}][serial_number]`,
                {
                  initialValue: product.serial_number,
                  rules: [{ required: true, message: 'Please insert status!' }],
                },
              ]"
              @search="serialModal(product, key)"
            >
              <a-button type="primary" slot="enterButton">
                <a-icon type="mobile" />
              </a-button>
            </a-input-search> </a-form-item
        ></a-col>
        <a-col :span="2">
          <a-form-item>
            <a-input
              @change="
                (e) => {
                  computedTotal(e, key);
                }
              "
              :disabled="product.has_serial_number"
              type="number"
              v-decorator="[
                `productItem[${key}][quantity]`,
                {
                  initialValue: product.quantity,
                },
              ]"
            /> </a-form-item
        ></a-col>
        <a-col :span="3">
          <a-form-item>
            <a-input
              :max="100"
              prefix="%"
              type="number"
              @change="(e) => discount(e, key)"
              v-decorator="[
                `productItem[${key}][discount]`,
                {
                  initialValue: 0,
                  rules: [],
                },
              ]"
            /> </a-form-item
        ></a-col>
        <a-col :span="3">
          <a-form-item>
            <a-input
              type="number"
              v-decorator="[
                `productItem[${key}][price]`,
                {
                  initialValue: 0,
                  initialValue: product.retail_price,
                  rules: [],
                },
              ]"
            /> </a-form-item
        ></a-col>
        <a-col :span="3">
          <a-form-item>
            <a-input
              type="number"
              v-decorator="[
                `productItem[${key}][min_price]`,
                {
                  initialValue: product.min_price,
                  rules: [],
                },
              ]"
            /> </a-form-item
        ></a-col>
        <a-col :span="2">
          <a-form-item>
            {{ products[key].total }}
          </a-form-item></a-col
        >
        <a-col :span="1"
          ><a-button v-on:click="removeRow(key)" type="link"
            ><a-icon type="delete" /></a-button
        ></a-col>
      </a-row>
    </a-form>
    <a-modal v-model="showSerialModal" width="70%" title="Select Serial">
      <serials v-if="showSerialModal" @getSerial="getSerial" :product="selectedProduct" />
    </a-modal>
  </div>
</template>
<script>
import {
  EVENT_CUSTOMERSALE_PRODUCT_SUMMARY,
  EVENT_CUSTOMERSALE_PRODUCT_ADD,
} from "../../services/constants";
import serials from "./../product/serials";

export default {
  components: { serials },
  data() {
    return {
      formLayout: "horizontal",
      form: this.$form.createForm(this, { name: "orders" }),
      products: {},
      uuid: 0,
      uuidString: "uuid-",
      total: 0,
      expendedTotal: 0,
      showSerialModal: false,
      selectedProduct: {},
    };
  },
  methods: {
    handleSubmit() {},
    getUid() {
      this.uuid = this.uuid + 1;
      return this.uuidString + this.uuid;
    },
    setProducts(product) {
      let uuidT = this.getUid();
      product.total = product.min_price;
      product.quantity = 1;
      let products = { ...this.products, [uuidT]: product };
      this.products = products;
      this.updateProducts(products);
    },
    removeRow(key) {
      let products = this.products;

      delete products[key];
      this.updateProducts(products);
    },
    computedTotal(event, key) {
      let quantity = event.target.value;
      this.updateQuantity(quantity, key);
    },
    updateQuantity(quantity, key) {
      let pp = this.products;

      pp[key].total = quantity * pp[key].min_price;
      pp[key].quantity = parseFloat(quantity);
      this.updateProducts(pp);
    },
    discount(value, key) {
      value = value.target.value;
      let pp = this.products;
      let price = pp[key].retail_price;

      pp[key].min_price = this.dicountFormula(price, value);

      this.updateProducts(pp);
      this.updateQuantity(pp[key].quantity, key);
    },
    dicountFormula(prices, discountPrices) {
      let dicountFormula = (prices / 100) * discountPrices;
      return prices - dicountFormula;
    },
    updateProducts(products) {
      products = JSON.stringify(products);
      this.products = JSON.parse(products);

      this.$eventBus.$emit(EVENT_CUSTOMERSALE_PRODUCT_SUMMARY, this.products);
    },
    serialModal(product, key) {
      product.key = key;
      this.selectedProduct = product;

      this.handleSearialModal(true);
    },
    handleSearialModal(show) {
      this.showSerialModal = show;
    },
    getSerial(product) {
      let products = this.products;
      products[this.selectedProduct.key].serial_number = product.serial_no;
      this.updateProducts(products);
      this.handleSearialModal(false);
    },
  },
  mounted() {
    let setProducts = this.setProducts;
    this.$eventBus.$on(EVENT_CUSTOMERSALE_PRODUCT_ADD, function (product) {
      setProducts(product);
    });
  },
  computed: {
    postedProducts: function () {
      return this.products;
    },
  },
};
</script>
<style scoped>
.d-none {
  display: none;
}
</style>
