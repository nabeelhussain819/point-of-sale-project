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
      <formFields :finance="finance" @onClose="show" />
    </a-modal>
  </div>
</template>
<script>
import formFields from "./form-fields";
import { EVENT_FINANCE_SHOWING_EDIT_MODAL } from "../../services/constants";
export default {
  components: { formFields },
  data() {
    return {
      visible: false,
      finance: null,
    };
  },
  methods: {
    show(show) {
      this.visible = show;
    },
    setFinance(finance) {
      this.finance = finance;
    },
  },
  mounted() {
    let show = this.show;
    let setFinance = this.setFinance;
    this.$eventBus.$on(EVENT_FINANCE_SHOWING_EDIT_MODAL, function (finance) {
      setFinance(finance);
      show(true);
    });
  },
};
</script>
