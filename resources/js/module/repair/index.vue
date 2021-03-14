<template>
  <div>
    <a-table :columns="columns" :data-source="data">
      <template slot="title">
        <a-button type="primary" v-on:click="showAddModal(true)">Add </a-button>
      </template>
      <a slot="name" slot-scope="text">{{ text }}</a>
      <span slot="customTitle"><a-icon type="smile-o" /> Name</span>
      <span slot="tags" slot-scope="tags">
        <a-tag
          v-for="tag in tags"
          :key="tag"
          :color="tag === 'loser' ? 'volcano' : tag.length > 5 ? 'geekblue' : 'green'"
        >
          {{ tag.toUpperCase() }}
        </a-tag>
      </span>
      <span slot="action" slot-scope="text, record">
        <a-icon v-on:click="edit(record.id)" type="edit" />
      </span>
    </a-table>
    <a-modal header="false" destroyOnClose="true" width="95%" v-model="addModal">
      <form-fields />
    </a-modal>
  </div>
</template>

<script>
import FormFields from "./formfields";
const columns = [
  {
    dataIndex: "name",
    key: "name",
    slots: { title: "Cusomer Name" },
    scopedSlots: { customRender: "name" },
  },
  {
    title: "Customer Number",
    dataIndex: "age",
    key: "age",
  },
  {
    title: "Status",
    key: "tags",
    dataIndex: "tags",
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
    };
  },
  methods: {
    edit(id) {
      console.log(id);
    },
    showAddModal(value) {
      this.addModal = value;
    },
  },
  components: {
    FormFields,
  },
};
</script>

<style scoped></style>
