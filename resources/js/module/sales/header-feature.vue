<template>
     <a-row class="sales-header" :gutter="24">
          <a-col :span="10">
            <a-divider orientation="left"> Product & Service </a-divider>
          </a-col>
          <a-col :span="2"> </a-col>
          <a-col :span="12">
            <a-divider orientation="left"> Customer </a-divider>
          </a-col>
          <a-col :span="5">
            <a-form-item label="Scan Product Number">
              <a-input
                v-decorator="[
                  'phone',
                  {
                    rules: [
                    
                    ],
                  },
                ]"
              /> </a-form-item
          ></a-col>
          <a-col :span="1"> <a-form-item :colon="false" label="OR">|</a-form-item></a-col>
          <a-col :span="6">
            <a-form-item label="Add Item">
              <a-button type="primary">Add Item </a-button>
            </a-form-item>
          </a-col>

          <a-col :span="12"><customer /></a-col>
        </a-row>
</template>
<script>
import customer from "./customer";
export default {
  components: {
    customer,
  },
};
</script>

<style scoped>
.sales-header{
      background-color: #cbcdff;
}
</style>