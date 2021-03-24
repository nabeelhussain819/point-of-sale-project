<template>
  <div>
    <a-row>
      <a-col :span="12">
        <a-descriptions>
          <a-descriptions-item label="Sub Total"> ${{ subTotal }} </a-descriptions-item>
          <a-descriptions-item label="Discount"> ${{ discount }} </a-descriptions-item>
          <a-descriptions-item label="Tax"> $00 </a-descriptions-item>
        </a-descriptions></a-col
      >
      <a-col :span="12">
        <a-button v-on:click="toggleModal(true)" type="primary"
          >Apply Discount / CheckOut</a-button
        >
        <a-popconfirm
          title="Are you sure cancel this transaction?"
          ok-text="Yes"
          cancel-text="No"
          @confirm="confirm"
          @cancel="cancel"
        >
          <a-button type="danger"> Cancel Transcation </a-button>
        </a-popconfirm>
      </a-col>
      <a-modal
        width="100%"
        :visible="showOrderInvoice"
        @cancel="toggleModal(false)"
        title=""
        footer=""
        ><product-table
      /></a-modal>
    </a-row>
  </div>
</template>

<script>
import productTable from "./products-table";
export default {
  components: { productTable },
  data() {
    return { showOrderInvoice: false, products: {}, subTotal: 0, discount: 0 };
  },
  methods: {
    toggleModal($show) {
      this.showOrderInvoice = $show;
    },
    confirm(e) {
      this.$message.success("Click on Yes");
    },
    cancel(e) {
      this.$message.error("Click on No");
    },
    calculate(products) {
      let total = 0;
      for (const key in products) {
        console.log(products[key]);
        total += products[key].total;
      }
      this.subTotal = total;
    },
  },
  mounted() {
    let calc = this.calculate;
    this.$eventBus.$on("PRODUCTSUMMARYEVENT", function (products) {
      calc(products);
      // this.products = products;
    });
  },
};
</script>
