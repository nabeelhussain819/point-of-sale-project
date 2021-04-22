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
      <a-skeleton :loading="loading">
        <basic-form
          v-if="!isCreated"
          :finance="finance"
          :isCreated="isCreated"
          @onClose="show"
        />
        <invoice :finance="finance" v-else />
      </a-skeleton>
    </a-modal>
  </div>
</template>
<script>
import basicForm from "./basic-form";
import FinanceService from "../../services/API/FinanceService";
import { EVENT_FINANCE_SHOWING_EDIT_MODAL } from "../../services/constants";
import Invoice from "./Invoice";
export default {
  components: { basicForm, Invoice },
  data() {
    return {
      visible: false,
      finance: {},
      isCreated: false,
      loading: false,
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
    setFinance(financeId) {
      this.loading = true;
      FinanceService.get(financeId.id)
        .then((finance) => {
          this.finance = finance;
          this.isCreated = true;
        })
        .finally(() => (this.loading = false));
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
