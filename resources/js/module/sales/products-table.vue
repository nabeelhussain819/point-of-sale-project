<template>
  <div>
    Customer :
    <span>
      <span v-if="!isEmpty(customer)">customer.name_phone</span>
      <span v-else>No Customer</span>
    </span>
    <a-table :columns="columns" :pagination="false" :data-source="products">
      <a slot="name" slot-scope="text">{{ text }}</a>
    </a-table>
  </div>
</template>
<script>
import { isEmpty } from "../../services/helpers";
const columns = [
  {
    title: "Name",
    dataIndex: "description",
    key: "description",
    ellipsis: true,
  },
  {
    title: "Serial",
    dataIndex: "serial",
    key: "serial",
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
  props: {
    products: { default: () => [] },
    customer: { default: () => {} },
  },
  data() {
    return {
      columns,
      isEmpty,
    };
  },
  mounted() {},
};
</script>
