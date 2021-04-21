<template>
  <a-card title="Finance" :bordered="false">
    <div slot="extra"><add @close="refresh" /></div>
    <list @getFetch="getFetch" />
  </a-card>
</template>

<script>
import list from "./list";
import add from "./add";
export default {
  components: { list, add },
  data() {
    return {
      fetch: () => {},
    };
  },
  methods: {
    refresh() {
      console.log();
      this.fetch();
    },
    getFetch(postedfunction) {
      this.fetch = postedfunction;
    },
  },
};
</script>
