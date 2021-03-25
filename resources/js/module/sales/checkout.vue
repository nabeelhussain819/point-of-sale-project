<template>
  <div>
    <a-row gutter="2">
      <a-form :form="form" :label-col="{ span: 24 }" :wrapper-col="{ span: 24 }">
        <a-tabs :animated="false" default-active-key="1" @change="callback">
          <a-tab-pane key="1" tab="Cash"> </a-tab-pane>
          <a-tab-pane key="2" tab="Card" force-render>
            <a-col :span="4">
              <a-form-item label="Card number">
                <a-input
                  type="card_number"
                  v-decorator="[
                    'card_number',
                    {
                      rules: [
                        { required: true, message: 'Please input your customer_number!' },
                      ],
                    },
                  ]" /></a-form-item
            ></a-col>
          </a-tab-pane>
        </a-tabs>

        <a-col :span="4">
          <a-form-item label="Cash">
            <!-- ------------------- -->

            <a-input
              type="number"
              v-decorator="[
                'price',
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
        <!-- <a-col :span="4">
          <a-form-item label="Customer Phone">
            <a-input
              type="number"
              v-decorator="[
                'phone',
                {
                  rules: [
                    { required: true, message: 'Please input your customer_number!' },
                  ],
                },
              ]" /></a-form-item
        ></a-col> -->
      </a-form>
    </a-row>
  </div>
</template>

<script>
export default {
  data() {
    return {
      formLayout: "horizontal",
      form: this.$form.createForm(this, { name: "addCustomer" }),
    };
  },
};
</script>
