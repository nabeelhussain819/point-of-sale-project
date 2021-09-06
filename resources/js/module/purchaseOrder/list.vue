<template>
    <div>
        <a-table :columns="columns" :data-source="data"> </a-table>
    </div>
</template>

<script>
import PurchaseOrderServices from "../../services/API/PurchaseOrderServices";
const columns = [
    {
        title: "Id",
        dataIndex: "id",
        key: "id"
    },
    {
        title: "Vendour Name",
        dataIndex: "vendor.name",
        key: "name"
    },
    {
        title: "Created date ",
        dataIndex: "created_date",
        key: "created_date"
    },
    {
        title: "Total",
        dataIndex: "price",
        key: "price"
    },
    {
        title: "Action",
        dataIndex: "total",
        key: "total"
    }
];
export default {
    data() {
        return {
            columns,
            data: []
        };
    },
    mounted() {
        this.fetch();
    },
    methods: {
        fetch(params = {}) {
            PurchaseOrderServices.all(params).then(response => {
                this.data = response.data;
            });
        }
    }
};
</script>
