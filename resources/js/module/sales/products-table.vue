<template>
  <div>
    <a-table :columns="columns" :pagination="false" :data-source="products">
      <a slot="name" slot-scope="text">{{ text }}</a>
    </a-table>
  </div>
</template>
<script>
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
  },
  data() {
    return {
      columns,
    };
  },
};
</script>
