<template>
    <a-table :pagination="false" :columns="columns" :data-source="data">
        <a slot="name" class="text-capitalize" slot-scope="text">{{ text }}</a>
        <a slot="total" class="text-capitalize" slot-scope="text"
            >{{ text }}$</a
        >
    </a-table>
</template>
<script>
export default {
    props: {
        data: {
            default: () => [],
            type: Array,
            required: true
        },
        columns: {
            default: () => [],
            type: Array,
            required: true
        }
    },
    data() {
        return {};
    }
};
</script>
