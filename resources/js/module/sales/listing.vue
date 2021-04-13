<template>
  <div>
    <a-skeleton :loading="loading">
      <a-table :columns="columns" :pagination="pagination" :data-source="data">
        <template slot="title">
          <a href="/sales/create">
            <a-button type="primary">Create </a-button>
          </a>
        </template>
        <a slot="name" slot-scope="text">{{ text }}</a>
        <span slot="customTitle"><a-icon type="smile-o" /> Name</span>
        <span slot="tags" slot-scope="tags">
          <a-tag color="volcano">
            {{
              tags.toUpperCase() === "COMPLETED" ? "Ready For Pickup" : tags.toUpperCase()
            }}
          </a-tag>
        </span>
        <span slot="action" slot-scope="text, record">
          <a-icon v-on:click="showPrintModal(record.id)" type="printer" />
        </span>
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

const columns = [
  {
    dataIndex: "id",
    key: "id",
    title: "Invoice",
  },
  {
    dataIndex: "customer.name",
    key: "name",
    title: "Customer Name",
  },
  {
    title: "Customer Number",
    dataIndex: "customer.phone",
    key: "customer.phone",
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
];

export default {
  components: { productTable },
  name: "index.vue",
  data() {
    return {
      data: [],
      showOrderInvoice: false,
      columns,
      loading: false,
      pagination: {},
      products: {},
      customer: {},
      billSummary: {},
      order: {},
    };
  },
  mounted() {
    this.fetchList();
  },
  methods: {
    toggleModal(show) {
      this.showOrderInvoice = show;
    },
    orderCreated(created) {
      this.isOrderCreated = created;
    },
    fetchList() {
      this.loading = true;
      OrderService.all()
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
  },
};
</script>

<style scoped></style>
