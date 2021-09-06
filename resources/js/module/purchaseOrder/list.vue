<template>
    <div>
        <a-table :columns="columns" :data-source="data">
            <span slot="action" slot-scope="text, record">
                <a-button v-if="!record.received_at" @click=goto(record) type="primary" htmlType="link"
                    >Receive </a-button
                >
                <span v-else>
                    <a-icon
                        type="check-circle"
                        theme="twoTone"
                        two-tone-color="#52c41a"
                />Received At {{record.received_at}}</span>
            </span>
        </a-table>
    </div>
</template>

<script>
import PurchaseOrderServices from "../../services/API/PurchaseOrderServices";
import { isEmpty } from "../../services/helpers";
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
        key: "total",
        scopedSlots: { customRender: "action" }
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
        isEmpty,
        fetch(params = {}) {
            PurchaseOrderServices.all(params).then(response => {
                this.data = response.data;
            });
        },
        goto(item){
            window.location.href = `/received-form/${item.id}`
        }
    }
};
</script>
