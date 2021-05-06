<template>
  <a-card title="Refund" :bordered="false">
    <list
      @totalRefundMoney="setRefund"
      @returnProducts="getReturnProducts"
      :order="order"
    />
    <a-row>
      <a-col :span="12"> <br /></a-col>

      <a-col :span="12">
        <br />
        <a-form :form="form" @submit="handleSubmit">
          <billing-summary :order="order" />
          <br />
          <a-col :span="12" offset="12">
            <a-button html-type="submit" type="primary">Return</a-button>
            <a-button type="danger">Cancel</a-button>
          </a-col>
        </a-form>
      </a-col>
    </a-row>
  </a-card>
</template>
<script>
import list from "./list";
import BillingSummary from "./billing-summary";
import RefundServices from "../../services/API/RefundServices";
import { isEmpty,notification } from "../../services/helpers";
export default {
  components: { list, BillingSummary },
  data() {
    return {
      formLayout: "horizontal",
      form: this.$form.createForm(this, { name: "refun" }),
      totalRefundMoney: 0,
      returnProducts: {},
    };
  },
  props: {
    order: {
      default: () => {},
      type: Object,
    },
  },
  methods: {
    setRefund(refund) {
      this.refund = refund;
      this.form.setFieldsValue({
        return_cost: this.getTotalwithTax(refund, this.getTaxValue(refund)),
      });
    },
    getTotalwithTax(total, taxPrice) {
      taxPrice = total + taxPrice;
      return taxPrice.toFixed(2);
    },
    getTaxValue(retailPrice) {
    
      let tax = isEmpty(this.order.tax) ? 0 : parseInt(this.order.tax);
      return (retailPrice / 100) * tax;
    },
    fetch() {
      // console.log(this.order);
    },
    getReturnProducts(returnProducts) {
      this.returnProducts = returnProducts;
    },
    handleSubmit(e) {
      e.preventDefault();
      this.form.validateFields((err, values) => {
        if (!err) {
          RefundServices.create({
            order_id: this.order.id,
            ...values,
            returnProducts: this.returnProducts,
          }).then((response) => {
             notification(this, response.message);
          });
        }
      });
    },
  },
  mounted() {
    this.fetch();
  },
};
</script>
