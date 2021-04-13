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
          <a-icon v-on:click="print(record.id)" type="printer" />
        </span>
      </a-table>
    </a-skeleton>
  </div>
</template>

<script>
import OrderService from "../../services/API/OrderServices";

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
  name: "index.vue",
  data() {
    return {
      data: [],
      columns,
      loading: false,
      pagination: {},
    };
  },
  mounted() {
    this.fetchList();
  },
  methods: {
    fetchList() {
      this.loading = true;
      OrderService.all()
        .then((data) => {
          this.data = data.data;
        })
        .finally(() => (this.loading = false));
    },
    print(orderId) {
      OrderService.show(orderId).then((order) => {
        console.log(order);
      });
    },
  },
  components: {},
};
</script>

<style scoped></style>
