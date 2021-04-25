<template>
  <div class="sales">
    <a-card :bordered="false">
      <div class="product-detail">
        <header-feature />
        <a-divider orientation="left"> Shoping List </a-divider>
        <products :preloadProduct="pre" @updatedProducts="updatedProducts" />
        <a-divider orientation="left"> Order Summary </a-divider>
        <product-summary />
      </div>
    </a-card>
  </div>
</template>
<script>
import headerFeature from "./header-feature";
import products from "./products";
import productSummary from "./product-summary";
export default {  
  

  props: {
    pre: {
      default: () => {},
      type: Object,
    },
  },
  data() {
    return {
      products: {},
    };
  },
  components: {
    headerFeature,
    products,
    productSummary,
  },
  methods: {
    updatedProducts(products) {
      console.log(products);
    },
  },
  mounted() {
    console.log("parent", this.pre);
  },
};
</script>
