<template>
    <div>
        <a-table :dataSource="data" :columns="columns"></a-table>
    </div>
</template>
<script>
import VendorService from "../../services/API/VendorService";
export default {
    data() {
        return {
            data: [],
            columns: [
                {
                    dataIndex: "id",
                    key: "id",
                    title: "Id",
                },
                {
                    dataIndex: "quantity",
                    key: "quantity",
                    title: "Quantity",
                },
                {
                    dataIndex: "vendor.name",
                    key: "vendor",
                    title: "vendor",
                },
                {
                    dataIndex: "product.name",
                    key: "product",
                    title: "product",
                },
            ],
        };
    },
    mounted() {
        this.fetchRefund();
    },
    methods: {
        fetchRefund() {
            VendorService.getRefundedList().then((response) => {
                this.data = response.data;
            });
        },
    },
};
</script>
