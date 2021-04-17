<template>
  <a-table
    :pagination="false"
    :columns="columns"
    :data-source="data"
    :row-selection="rowSelection"
  >
    <a slot="tax">{{ order.tax }}</a>
    <span slot="returnQuantity" slot-scope="text, record">
      <a-form-item type="number" width="50px" class="pos-form-item margin-0">
        <a-input-number
          :disababled="order.disabled"
          :min="0"
          v-on:change="(value) => refundQuantity(value, record)"
          :max="parseInt(record.quantity)"
          type="number"
          v-decorator="[
            `productItem[${record.id}][quantity]`,
            {
              initialValue: record.quantity,
              rules: [],
            },
          ]"
        />
      </a-form-item>
      <span class="quantity-container"> / {{ parseInt(record.quantity) }}</span>
    </span>
  </a-table>
</template>
<script>
import { isEmpty } from "../../services/helpers";
const columns = [
  {
    dataIndex: "id",
    key: "id",
    title: "Invoice Number",
  },
  {
    title: "Product Name",
    dataIndex: "product.name",
    key: "product.name",
  },
  {
    title: "Quantity",
    dataIndex: "quantity",
    key: "quantity",
  },
  {
    title: "Retail Price",
    dataIndex: "retail_price",
    key: "quaretail_pricentity",
  },
  {
    title: "Min Price",
    dataIndex: "min_price",
    key: "min_price",
  },
  {
    title: "Tax",
    dataIndex: "tax",
    key: "tax",
    scopedSlots: { customRender: "tax" },
  },
  {
    title: "Return Quantity",
    dataIndex: "address",
    scopedSlots: { customRender: "returnQuantity" },
  },
];

export default {
  props: {
    order: {
      default: () => {},
    },
  },
  data() {
    return {
      data: [],
      columns,
      finalErrors: {},
      isEmpty,
      disabledList: {},
      returnAmount: 0,
      returnProducts: {},
    };
  },
  methods: {
    refundQuantity(value, record) {     
      this.handleRefunds(value, record);
    },
    handleRefunds(value, record) {
      let returnProducts = this.returnProducts;
      
      returnProducts[record.id] = {
        product_id: record.product_id,
        quantity: value,
        return_cost: value * parseInt(record.min_price),
        serial_no:record.serial_number,
        order_id:this.order.id
      };
      this.updateReturns(returnProducts);
    },
    updateReturns(returnProducts) {
      this.returnProducts = returnProducts;
      this.summary(returnProducts);
    },
    summary(returnProducts) {
      let totalRefundMoney = 0;
      for (const product in returnProducts) {
        totalRefundMoney += returnProducts[product].return_cost;
      }
     
      this.$emit("totalRefundMoney",totalRefundMoney);      
      this.$emit("returnProducts",returnProducts);      
    },
  },
  mounted() {
    this.data = this.order.products;
  },
  computed: {
    rowSelection() {
      return {
        onSelect: (record, selected, selectedRows) => {
          // console.log(record, selected, selectedRows);
        },
      };
    },
  },
};
</script>
<style>
.margin-0 {
  margin: 0;
}
.pos-form-item {
  width: 50px;
  float: left;
}
.quantity-container {
  padding: 15px;
  line-height: 2.5em;
}
</style>
