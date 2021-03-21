<template>
  <div>
    <a-card :bordered="false">
      <div class="product-detail">
        <header-feature />
        <a-divider orientation="left"> Shoping List </a-divider>
        <products />
        <a-divider orientation="left"> Order Summary </a-divider>
        <product-summary/>
      </div>
    </a-card>
  </div>
</template>
<script>
import headerFeature from "./header-feature";
import products from "./products";
import productSummary from "./product-summary";
export default {
  components: {
    headerFeature,
    products,
    productSummary
  },
  
};
</script>
