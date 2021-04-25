<template>
  <a-table class="condense-table table-bordered table-condensed" :data-source="data" :loading="loading" :columns="columns">
    <div
      slot="filterDropdown"
      slot-scope="{ setSelectedKeys, selectedKeys, confirm, clearFilters, column }"
      style="padding: 8px"
    >
      <a-input
        v-ant-ref="(c) => (searchInput = c)"
        :placeholder="`Search ${column.dataIndex}`"
        :value="selectedKeys[0]"
        style="width: 188px; margin-bottom: 8px; display: block"
        @change="(e) => setSelectedKeys(e.target.value ? [e.target.value] : [])"
        @pressEnter="() => handleSearch(selectedKeys, column)"
      />
      <a-button
        type="primary"
        icon="search"
        size="small"
        style="width: 90px; margin-right: 8px"
        @click="() => handleSearch(selectedKeys, column)"
      >
        Search
      </a-button>
      <a-button
        size="small"
        style="width: 90px"
        @click="() => handleReset(selectedKeys, column)"
      >
        Reset
      </a-button>
    </div>
    <div
      slot="selectDropdown"
      slot-scope="{ setSelectedKeys, selectedKeys, confirm, clearFilters, column }"
      style="padding: 8px"
    >
      <a-select
        style="width: 100%; margin-bottom: 5px"
        v-ant-ref="(c) => (searchInput = c)"
        @change="(value) => setSelectedKeys(value ? [value] : [])"
        placeholder="Select a option and change input text above"
        @pressEnter="() => handleSearch(selectedKeys, column)"
      >
        <a-select-option v-for="type in types" :key="type.id">
          {{ type.name }}</a-select-option
        >
      </a-select>
      <!-- <a-input
        v-ant-ref="(c) => (searchInput = c)"
        :placeholder="`Search ${column.dataIndex}`"
        :value="selectedKeys[0]"
        style="width: 188px; margin-bottom: 8px; display: block"
      /> -->
      <a-button
        type="primary"
        icon="search"
        size="small"
        style="width: 90px; margin-right: 8px"
        @click="() => handleSearch(selectedKeys, column)"
      >
        Search
      </a-button>
      <a-button
        size="small"
        style="width: 90px"
        @click="() => handleReset(selectedKeys, column)"
      >
        Reset
      </a-button>
    </div>

    <a-icon
      slot="filterIcon"
      slot-scope="filtered"
      type="search"
      :style="{ color: filtered ? '#108ee9' : undefined }"
    />

    <span slot="action" slot-scope="text, record">
      <a-button v-on:click="showModal(record)" type="link">view</a-button>
    </span>
  </a-table>
</template>

<script>
import FinanceService from "../../services/API/FinanceService";
import {
  EVENT_FINANCE_ADD_RECORD,
  EVENT_FINANCE_SHOWING_EDIT_MODAL,
  FINANCE_TYPE,
} from "../../services/constants";

export default {
  data() {
    return {
      data: [],
      searchText: "",
      searchInput: null,
      searchedColumn: "",
      types: FINANCE_TYPE,
      columns: [
        {
          title: "Name",
          dataIndex: "customer_name",
          key: "customerName",
          scopedSlots: {
            filterDropdown: "filterDropdown",
            filterIcon: "filterIcon",
          },
        },
        {
          title: "Product",
          dataIndex: "product.name",
          key: "product_name",
          scopedSlots: {
            filterDropdown: "filterDropdown",
            filterIcon: "filterIcon",
          },
        },
        {
          title: "Total",
          dataIndex: "total",
          key: "total",
        },
        {
          title: "Paid",
          dataIndex: "advance",
          key: "advance",
        },
        {
          title: "Remaining",
          dataIndex: "payable",
          key: "payable",
        },
        {
          title: "Type",
          dataIndex: "type",
          key: "type",
          filterMultiple: false,
          scopedSlots: {
            filterDropdown: "selectDropdown",
            filterIcon: "filterIcon",
          },
          filters: [],
        },
        {
          title: "Action",
          key: "action",
          scopedSlots: { customRender: "action" },
        },
      ],
      loading: false,
      filters: {},
    };
  },
  methods: {
    showModal(finance) {
      this.$eventBus.$emit(EVENT_FINANCE_SHOWING_EDIT_MODAL, finance);
    },
    handleSearch(value, column) {
      let filters = this.filters;
      filters[column.key] = value[0];
      this.setfilters(filters);
    },
    handleReset(value, column) {
      let filters = this.filters;
      delete filters[column.key];
      this.setfilters(filters);
    },
    setfilters(filters) {
      this.filters = JSON.parse(JSON.stringify(filters));
      this.fetch(this.filters);
    },
    fetch(params = {}) {
      this.loading = true;
      FinanceService.all(params)
        .then((finance) => {
          this.data = finance.data;
        })
        .finally(() => (this.loading = false));
    },
  },
  mounted() {
    let fetch = this.fetch;
    this.$emit("getFetch", fetch);
    this.$eventBus.$on(EVENT_FINANCE_ADD_RECORD, function (product) {
      if (product) {
        fetch();
      }
    });
    this.fetch();
  },
};
</script>
