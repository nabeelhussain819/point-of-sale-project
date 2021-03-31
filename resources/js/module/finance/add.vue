<template>
  <div>
    <a-button v-on:click="show(true)" type="primary">Add</a-button>
    <a-modal
      :visible="visible"
      @cancel="show(false)"
      :footer="null"
      width="98%"
      title="Add Finance"
      :destroyOnClose="true"
    >
      <formFields @onClose="show" />
    </a-modal>
  </div>
</template>
<script>
import formFields from "./form-fields";
export default {
  components: { formFields },
  data() {
    return {
      visible: false,
    };
  },
  methods: {
    show(show) {
      this.visible = show;
    },
  },
};
</script>
