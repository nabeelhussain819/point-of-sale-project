<template>
  <div>
    <a-card title="Sales" :bordered="false">
      <div class="header">
      <header-feature/>
      </div>
    </a-card>
  </div>
</template>
<script>
import headerFeature from "./header-feature";
export default {
  components: {
    headerFeature,
  },
};
</script>
