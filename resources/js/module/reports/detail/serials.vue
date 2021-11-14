<template>
    <a-table :columns="columns" :data-source="data"> </a-table>
</template>
<script>
const columns = [
    {
        dataIndex: "serial_number",
        key: "serial_number",
        title:"Serial Number"
    }
];

export default {
    props: {
        data: { default: [], type: Array, require: true }
    },
    data() {
        return {
            columns
        };
    }
};
</script>
