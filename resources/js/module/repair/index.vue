<template>
  <div>
    <a-skeleton :loading="loading">
      <a-table :columns="columns" :data-source="data">
        <template slot="title">
          <a-button type="primary" v-on:click="showAddModal(true)">Add </a-button>
        </template>
        <a slot="name" slot-scope="text">{{ text }}</a>
        <span slot="customTitle"><a-icon type="smile-o" /> Name</span>
        <span slot="tags" slot-scope="tags">
          <a-tag color="volcano">
            {{ tags.toUpperCase() }}
          </a-tag>
        </span>
        <span slot="action" slot-scope="text, record">
          <a-icon v-on:click="edit(record.id)" type="edit" />
        </span>
      </a-table>
    </a-skeleton>
    <a-modal header="false" :destroyOnClose="true" width="95%" v-model="addModal">
      <form-fields :repairId="repairId" @close="showAddModal()" />
    </a-modal>
  </div>
</template>

<script>
import FormFields from "./formfields";
import RepairService from "./services/API/RepairService";
const columns = [
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
    title: "Days",
    dataIndex: "days",
    key: "days",
  },
  {
    title: "Status",
    key: "status",
    dataIndex: "status",
    scopedSlots: { customRender: "tags" },
  },
  {
    title: "Action",
    key: "action",
    scopedSlots: { customRender: "action" },
  },
];

const data = [
  {
    id: 1,
    key: "1",
    name: "John Brown",
    age: 32,
    address: "New York No. 1 Lake Park",
    tags: ["nice", "developer"],
  },
  {
    id: 2,
    key: "2",
    name: "Jim Green",
    age: 42,
    address: "London No. 1 Lake Park",
    tags: ["loser"],
  },
  {
    id: 3,
    key: "3",
    name: "Joe Black",
    age: 32,
    address: "Sidney No. 1 Lake Park",
    tags: ["cool", "teacher"],
  },
];
export default {
  name: "index.vue",
  data() {
    return {
      data,
      columns,
      addModal: false,
      deviceTypes: [],
      brands: [],
      products: [],
      loading: true,
      repairId: null,
    };
  },
  mounted() {
    this.fetchList();
  },
  methods: {
    edit(id) {
      this.repairId = id;
      this.showAddModal(true);
    },
    showAddModal(value) {
      if (!value) {
        this.fetchList();
      }
      this.addModal = value;
    },
    fetchList() {
      this.loading = true;
      RepairService.all()
        .then((data) => {
          this.data = data;
        })
        .finally(() => (this.loading = false));
    },
  },
  components: {
    FormFields,
  },
};
</script>

<style scoped></style>
