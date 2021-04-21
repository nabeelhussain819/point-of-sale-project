<template>
  <a-table :data-source="data" :loading="loading" :columns="columns">
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
        @pressEnter="() => handleSearch(selectedKeys, confirm, column.dataIndex)"
      />
      <a-button
        type="primary"
        icon="search"
        size="small"
        style="width: 90px; margin-right: 8px"
        @click="() => handleSearch(selectedKeys, confirm, column.dataIndex)"
      >
        Search
      </a-button>
      <a-button size="small" style="width: 90px" @click="() => handleReset(clearFilters)">
        Reset
      </a-button>
    </div>
    <a-icon
      slot="filterIcon"
      slot-scope="filtered"
      type="search"
      :style="{ color: filtered ? '#108ee9' : undefined }"
    />
    <template slot="customRender" slot-scope="text, record, index, column">
      <span v-if="searchText && searchedColumn === column.dataIndex">
        <template
          v-for="(fragment, i) in text
            .toString()
            .split(new RegExp(`(?<=${searchText})|(?=${searchText})`, 'i'))"
        >
          <mark
            v-if="fragment.toLowerCase() === searchText.toLowerCase()"
            :key="i"
            class="highlight"
            >{{ fragment }}</mark
          >
          <template v-else>{{ fragment }}</template>
        </template>
      </span>
      <template v-else>
        {{ text }}
      </template>
    </template>

    <span slot="action" slot-scope="text, record">
      <a-button v-on:click="showModal(record)" type="link">edit</a-button>
    </span>
  </a-table>
</template>

<script>
import FinanceService from "../../services/API/FinanceService";
import {
  EVENT_FINANCE_ADD_RECORD,
  EVENT_FINANCE_SHOWING_EDIT_MODAL,
} from "../../services/constants";

export default {
  data() {
    return {
      data: [],
      searchText: "",
      searchInput: null,
      searchedColumn: "",
      columns: [
        {
          title: "Name",
          dataIndex: "customer_name",
          key: "customer.id",
          scopedSlots: {
            filterDropdown: "filterDropdown",
            filterIcon: "filterIcon",
            customRender: "customRender",
          },
          onFilter: (value, record) =>
            record.name.toString().toLowerCase().includes(value.toLowerCase()),
          onFilterDropdownVisibleChange: (visible) => {
            if (visible) {
              setTimeout(() => {
                this.searchInput.focus();
              }, 0);
            }
          },
        },
        {
          title: "Product",
          dataIndex: "product.name",
          key: "address",
          scopedSlots: {
            filterDropdown: "filterDropdown",
            filterIcon: "filterIcon",
            customRender: "customRender",
          },
          onFilter: (value, record) =>
            record.address.toString().toLowerCase().includes(value.toLowerCase()),
          onFilterDropdownVisibleChange: (visible) => {
            if (visible) {
              setTimeout(() => {
                this.searchInput.focus();
              });
            }
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
          scopedSlots: {
            filterDropdown: "filterDropdown",
            filterIcon: "filterIcon",
            customRender: "customRender",
          },
          onFilter: (value, record) =>
            record.address.toString().toLowerCase().includes(value.toLowerCase()),
          onFilterDropdownVisibleChange: (visible) => {
            if (visible) {
              setTimeout(() => {
                this.searchInput.focus();
              });
            }
          },
        },
        {
          title: "action",
          key: "action",
          scopedSlots: { customRender: "action" },
        },
      ],
      loading: false,
    };
  },
  methods: {
    showModal(finance) {
      this.$eventBus.$emit(EVENT_FINANCE_SHOWING_EDIT_MODAL, finance);
    },
    handleSearch(selectedKeys, confirm, dataIndex) {
      confirm();
      this.searchText = selectedKeys[0];
      this.searchedColumn = dataIndex;
    },
    handleReset(clearFilters) {
      clearFilters();
      this.searchText = "";
    },
    fetch(params = {}) {
      this.loading = true;
      FinanceService.all()
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
