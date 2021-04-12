<template>
  <div class="no-print">
    <a-row :gutter="2">
      <!-- <a-form :form="form" :label-col="{ span: 24 }" :wrapper-col="{ span: 24 }"> -->
      <a-radio-group
        class="no-print"
        name="radioGroup"
        @change="paymentMode"
        default-value="cash"
      >
        <a-radio value="cash"> Cash </a-radio>
        <a-radio value="card"> Card </a-radio>
      </a-radio-group>
      <br /><br />

      <a-col v-if="!isCash" :span="24">
        <a-form-item label="Card Number">
          <a-input
            type="card_number"
            v-decorator="[
              'customer_card_number',
              {
                rules: [
                  { required: true, message: 'Please input your customer_number!' },
                ],
              },
            ]" /></a-form-item
      ></a-col>
      <a-col :span="24">
        <a-form-item label="Amount">
          <!-- ------------------- -->

          <a-input
            v-on:change="getAmount"
            type="number"
            v-decorator="[
              'cash_paid',
              {
                rules: [
                  { required: true, message: 'Please input your customer_number!' },
                ],
              },
            ]"
          />
          <!-- ------------------------------ -->
        </a-form-item></a-col
      >
      <a-col v-if="isCash" :span="24">
        <a-form-item label="Cash Back">
          <!-- ------------------- -->
          <a-input
            :disabled="true"
            type="number"
            v-decorator="[
              'cash_back',
              {
                rules: [],
              },
            ]"
          />
          <!-- ------------------------------ -->
        </a-form-item></a-col
      >

      <!-- </a-form> -->
    </a-row>
  </div>
</template>

<script>
export default {
  props: { summary: { default: () => {} } },
  data() {
    return {
      isCash: true,
    };
  },
  methods: {
    paymentMode(e) {
      this.isCash = e.target.value === "cash";
    },
    getAmount(e) {
     
      let cash = e.target.value;
      let total = this.summary.subTotal;
      let cashBack = cash - total;
      if (cashBack > 0) {
        this.$emit("cashBack", cashBack);
      } else {
        this.$emit("cashBack", 0);
      }
    },
  },
};
</script>
