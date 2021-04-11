<template>
  <a-table
    :loading="loading"
    :columns="columns"
    :data-source="records"
    :row-selection="rowSelection"
  />
</template>
<script>
import ProductService from "../../services/API/ProductService";
const columns = [
  {
    title: "Serials",
    dataIndex: "serial_no",
    key: "serial_no",
  },
];

export default {
  props: {
    product: { default: () => {} },
  },
  data() {
    return {
      loading: true,
      records: [],
      columns,
    };
  },
  methods: {
    fetch() {
      ProductService.getSerials(this.product.id)
        .then((serials) => {
          this.records = serials.data;
        })
        .finally(() => (this.loading = false));     
    },
  },
  mounted() {
    this.fetch();
  },
  computed: {
    rowSelection() {
      return {
        onSelect: (record, selected, selectedRows) => {          
          this.$emit("getSerial",record)
        },        
      };
    },
  },
};
</script>
