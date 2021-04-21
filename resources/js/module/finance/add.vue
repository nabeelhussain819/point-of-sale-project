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
      <basic-form :finance="finance" :isCreated="isCreated" @onClose="show" />
    </a-modal>
  </div>
</template>
<script>
import basicForm from "./basic-form";
import { EVENT_FINANCE_SHOWING_EDIT_MODAL } from "../../services/constants";
export default {
  components: { basicForm },
  data() {
    return {
      visible: false,
      finance: {},
      isCreated: false,
    };
  },
  methods: {
    show(show) {
      this.visible = show;

      if (!show) {
        this.isCreated = false;
        this.$emit("close");
        this.finance = {};
      }
    },
    setFinance(finance) {
      this.finance = finance;
      this.isCreated = true;
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
