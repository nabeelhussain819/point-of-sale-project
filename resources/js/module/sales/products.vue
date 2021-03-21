<template>
  <div>
    <a-row :gutter="23">
      <a-col :span="1"><strong>Id</strong></a-col>
      <a-col :span="7"><strong>Description</strong></a-col>
      <a-col :span="4"><strong>Serial</strong></a-col>
      <a-col :span="4"><strong>Quantity</strong></a-col>
      <a-col :span="4"><strong>Unit Prices</strong></a-col>
      <a-col :span="4"><strong>Extended Prices</strong></a-col>
    </a-row>
    <a-form
      :form="form"
      :label-col="{ span: 24 }"
      :wrapper-col="{ span: 24 }"
      @submit="handleSubmit"
    >
      <a-row :gutter="23">
        <a-col :span="1"></a-col>
        <a-col :span="7">
          <a-form-item>
            <a-input
              disabled
              v-decorator="[
                'id',
                {
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
                'description',
                {
                  rules: [],
                },
              ]"
            /> </a-form-item
        ></a-col>
        <a-col :span="4">
          <a-form-item>
            <a-input
              type="number"
              v-decorator="[
                'quantity',
                {
                  rules: [],
                },
              ]"
            /> </a-form-item
        ></a-col>
        <a-col :span="4">
          <a-form-item>
            <a-input
              type="number"
              v-decorator="[
                'price',
                {
                  rules: [],
                },
              ]"
            /> </a-form-item
        ></a-col>
        <a-col :span="4">
          <a-form-item>
            <a-input
              type="number"
              v-decorator="[
                'extended_price',
                {
                  rules: [],
                },
              ]"
            /> </a-form-item
        ></a-col>
      </a-row>
    </a-form>
  </div>
</template>
<script>
export default {
  data() {
    return {
      formLayout: "horizontal",
      form: this.$form.createForm(this, { name: "customer" }),
    };
  },
  methods: {
    handleSubmit() {},
  },
};
</script>
