<template>
  <div>
    <a-col :span="6">
      <a-form-item label="Card Phone">
        <a-input v-decorator="['customer_card_number']" /></a-form-item
    ></a-col>

    <a-col :span="6">
      <a-form-item label="Card Expiry Date"
        ><a-date-picker
          v-decorator="['customer_card_expiry']"
          width="100px"
        /> </a-form-item
    ></a-col>

    <a-col :span="6">
      <a-form-item label="Card CCV">
        <a-input v-decorator="['customer_card_ccv']" /></a-form-item
    ></a-col>
  </div>
</template>
<script>
export default {
  data() {
    return {
      cardDetails: {},
    };
  },
};
</script>
