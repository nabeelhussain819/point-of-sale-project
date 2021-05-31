<template>
  <div>
    <a-skeleton :loading="loading">
      <a-table  class="condense-table table-bordered table-condensed" :columns="columns" :pagination="pagination" :data-source="data">
        <template slot="title">
          <a href="/sales/create">
            <a-button type="primary">Create </a-button>
          </a>
        </template>
        <a slot="name" slot-scope="text">{{ text }}</a>
        <span slot="customTitle"><a-icon type="smile-o" /> Name</span>
        <!-- <span slot="tags" slot-scope="tags">
          <a-tag color="volcano">
            {{
              tags.toUpperCase() === "COMPLETED" ? "Ready For Pickup" : tags.toUpperCase()
            }}
          </a-tag>
        </span> -->
        <span slot="action" slot-scope="text, record">
          <a-icon v-on:click="showPrintModal(record.id)" type="printer" />
          <a :href="href(record.id)">refund</a>
        </span>
        <div
          slot="filterDropdown"
          slot-scope="{ setSelectedKeys, selectedKeys,  column }"
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
      </a-table>
    </a-skeleton>

    <a-modal
      :destroyOnClose="true"
      width="100%"
      :visible="showOrderInvoice"
      @cancel="toggleModal(false)"
      title=""
      footer=""
      ><product-table
        :createdOrder="order"
        :isCreated="true"
        @orderCreated="orderCreated"
        :products="products"
        :customer="customer"
        :billSummary="billSummary"
    /></a-modal>
  </div>
</template>

<script>
import OrderService from "../../services/API/OrderServices";
import productTable from "./products-table";
import { isEmpty } from "../../services/helpers";

export default {
  components: { productTable },
  name: "index.vue",
  data() {
    return {
      isEmpty,
      data: [],
      showOrderInvoice: false,
      columns: [
        {
          dataIndex: "id",
          key: "id",
          title: "Invoice",
          scopedSlots: {
            filterDropdown: "filterDropdown",
            filterIcon: "filterIcon",
          },
        },
        {
          dataIndex: "customer.name",
          key: "customerName",
          title: "Customer Name",
          scopedSlots: {
            filterDropdown: "filterDropdown",
            filterIcon: "filterIcon",
          },
        },
        {
          title: "Customer Number",
          dataIndex: "customer.phone",
          key: "customerPhone",
          scopedSlots: {
            filterDropdown: "filterDropdown",
            filterIcon: "filterIcon",
          },
        },
        {
          title: "Date",
          dataIndex: "date",
          key: "date",
        },
        {
          title: "Action",
          key: "action",
          scopedSlots: { customRender: "action" },
        },
      ],
      loading: false,
      pagination: {},
      products: {},
      customer: {},
      billSummary: {},
      order: {},
      filters: {},
    };
  },
  mounted() {
    this.fetchList();
  },
  methods: {
    href(id) {
      return `refund/order/${id}`;
    },
    toggleModal(show) {
      this.showOrderInvoice = show;
    },
    orderCreated(created) {
      this.isOrderCreated = created;
    },
    fetchList(params = {}) {
      this.loading = true;
      OrderService.all(params)
        .then((data) => {
          this.data = data.data;
        })
        .finally(() => (this.loading = false));
    },
    showPrintModal(orderId) {
      OrderService.show(orderId).then((order) => {
        this.customer = order.customer;
        this.products = order.products;
        this.billSummary = {
          discount: order.discount,
          withoutTax: order.without_tax,
          subTotal: order.sub_total,
          withoutDiscount: order.without_discount,
          tax: order.tax,
        };
        this.toggleModal(true);
        this.order = order;
      });
    },
    setfilters(filters) {
      this.filters = JSON.parse(JSON.stringify(filters));  
      this.fetchList(this.filters);
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
  },
};
</script>

<style scoped></style>
