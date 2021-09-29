<template>
    <div>
        <a-divider>Schedules</a-divider>
        <a-table :columns="columns" :data-source="repair.schedules">
            <a slot="prices" slot-scope="text">$ {{ text }}</a>
        </a-table>
    </div>
</template>
<script>
const columns = [
    {
        title: "Received Amount",
        dataIndex: "received_amount",
        key: "received_amount",
        scopedSlots: { customRender: "name" }
    },

    {
        title: "Discount",
        dataIndex: "discount",
        key: "discount",
        scopedSlots: { customRender: "name" }
    },
    {
        title: "Additional Charge",
        dataIndex: "additional_charge",
        key: "additional_charge",
        scopedSlots: { customRender: "name" }
    },
    {
        title: "Comment",
        dataIndex: "comment",
        key: "comment",
        ellipsis: true
    },
    {
        title: "Created At",
        dataIndex: "created_at",
        key: "created_at",
        ellipsis: true
    }
];

export default {
    props: {
        repair: { default: {} }
    },
    data() {
        return {
            columns
        };
    }
};
</script>
