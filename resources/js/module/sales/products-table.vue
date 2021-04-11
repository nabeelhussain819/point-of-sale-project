<template>
  <div>
    Customer :
    <span>
      <span v-if="!isEmpty(customer)">{{ customer.name_phone }}</span>
      <span v-else>No Customer</span>
    </span>
    <a-table :columns="columns" :pagination="false" :data-source="products">
      <a slot="name" slot-scope="text">{{ text }}</a>
    </a-table>
    <checkout />
    <a-button @click="checkout" type="primary">Checkout</a-button>
    <a-button type="button">Print</a-button>
  </div>
</template>
<script>
import { isEmpty } from "../../services/helpers";
import OrderService from "../../services/API/OrderServices";
import checkout from "./checkout";
const columns = [
  {
    title: "Name",
    dataIndex: "description",
    key: "description",
    ellipsis: true,
  },
  {
    title: "Serial",
    dataIndex: "serial_number",
    key: "serial_number",
  },
  {
    title: "Unit Prices",
    dataIndex: "retail_price",
    key: "retail_price",
  },
  {
    title: "Extended Price",
    dataIndex: "min_price",
    key: "min_price",
  },
  {
    title: "Quantity",
    dataIndex: "quantity",
    key: "quantity",
  },
];

export default {
  components: {
    checkout,
  },
  props: {
    products: { default: () => [] },
    customer: { default: () => {} },
    billSummary: { default: () => {} },
  },
  data() {
    return {
      columns,
      isEmpty,
    };
  },
  methods: {
    checkout() {
      let data = {
        products: this.products,
        customer: this.customer,
        summary: this.billSummary,
      };
      OrderService.create(data).then((response) => {
        console.log(response);
      });
     
    },
  },
  mounted() {
    console.log(this.products);
  },
};
</script>
