<template>
  <div>
    <a-row :gutter="23">
      <a-col :span="1"><strong>Id</strong></a-col>
      <a-col :span="3"><strong>Name</strong></a-col>
      <a-col :span="6"><strong>Description</strong></a-col>
      <a-col :span="4"><strong>Serial</strong></a-col>
      <a-col :span="3"><strong>Quantity</strong></a-col>
      <a-col :span="3"><strong>Unit Prices</strong></a-col>
      <a-col :span="3"><strong>Extended Prices</strong></a-col>
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
        <a-col :span="6">
          <a-form-item>
            <a-input
              disabled
              v-decorator="[
                `productItem[${key}][description]`,
                {
                  initialValue: product.description,
                  rules: [],
                },
              ]"
            />
            <a-input
              disabled
              class="d-none"
              v-decorator="[
                `productItem[${key}][id]`,
                {
                  initialValue: product.id,
                  rules: [],
                },
              ]"
            /> </a-form-item
        ></a-col>
        <a-col :span="4">
          <a-form-item>
            <a-input
              disabled
              v-decorator="[
                `productItem[${key}][serial_number]`,
                {
                  initialValue: product.serial_number,
                  rules: [],
                },
              ]"
            /> </a-form-item
        ></a-col>
        <a-col :span="3">
          <a-form-item>
            <a-input
              @change="
                (e) => {
                  computedTotal(e, key);
                }
              "
              type="number"
              v-decorator="[
                `productItem[${key}][quantity]`,
                {
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
                  initialValue: product.cost,
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
                `productItem[${key}][extended_price]`,
                {
                  initialValue: product.retail_price,
                  rules: [],
                },
              ]"
            /> </a-form-item
        ></a-col>
        <a-col :span="1"
          ><a-button v-on:click="removeRow(key)" type="link"
            ><a-icon type="delete" /></a-button
        ></a-col>
      </a-row>
      {{ total }}
    </a-form>
  </div>
</template>
<script>
export default {
  data() {
    return {
      formLayout: "horizontal",
      form: this.$form.createForm(this, { name: "orders" }),
      products: {},
      uuid: 0,
      uuidString: "uuid-",
      total: 0,
      expendedTotal: 0,
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
      this.products = { ...this.products, [uuidT]: product };
    },
    removeRow(key) {
      let products = this.products;

      delete products[key];

      let a = JSON.stringify(products);

      this.products = JSON.parse(a);
      console.log(this.products);
    },
    computedTotal(event, key) {
      let quantity = event.target.value;

      // let products = this.products;
      const fieldsValue = this.form.getFieldsValue();
      let total = 0;
      for (const product in fieldsValue.productItem) {
        let productQuantity = fieldsValue.productItem[product].quantity;
        if (product !== key && productQuantity) {
          total =
            total +
            parseFloat(productQuantity) * parseFloat(fieldsValue.productItem[key].price);
          console.log("in", total);
        }
      }
      let currentQuantity = quantity * parseFloat(fieldsValue.productItem[key].price);
      console.log("currentQuantity", currentQuantity);
      this.total = total + currentQuantity;
      console.log("final", total);
    },
  },
  mounted() {
    let setProducts = this.setProducts;

    this.$eventBus.$on("PRODUCTEVENT", function (product) {
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
