<template>
    <div>
        <a-table :columns="columns" :data-source="data">
            <a slot="name" slot-scope="text">{{ text }}</a>
        </a-table>
    </div>
</template>
<script>
const columns = [
    {
        title: "Doc",
        dataIndex: "properties.doc",
        key: "doc"
    },
    {
        title: "Doc/Action/Type",
        dataIndex: "properties.subject",
        key: "subject"
    },
    {
        title: "Date",
        dataIndex: "properties.date",
        key: "date",
        ellipsis: true
    },
    {
        title: "From",
        dataIndex: "properties.from",
        key: "from",
        ellipsis: true
    },
    {
        title: "To",
        dataIndex: "properties.to",
        key: "to",
        ellipsis: true
    },
    {
        title: "Amount",
        dataIndex: "properties.amount",
        key: "amount"
    },
    {
        title: "Posted By",
        dataIndex: "properties.postedBy",
        key: "postedBy"
    }
];

export default {
    props: {
        data: { default: () => [] }
    },

    data() {
        return {
            columns
        };
    }
};
</script>
