<template>
  <a-card title="Finance" :bordered="false">
    <div slot="extra"><add /></div>
    <list />
  </a-card>
</template>

<script>
import list from "./list";
import add from "./add";
export default {
  components: { list, add },
  data() {
    return {};
  },
};
</script>
