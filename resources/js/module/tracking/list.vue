<template>
    <div>
        <a-table :columns="columns" :data-source="data">
            <span slot="docType" slot-scope="text, record">
                <a-button type="link" @click="detail(text, record)">{{
                    record.properties.subject
                }}</a-button>
            </span>
        </a-table>
        <a-modal
            :footer="null"
            v-if="visible"
            @cancel="showModal(false)"
            v-model="visible"
            :title="subject"
        >
            <detail :tracking_id="tracking_id" />
        </a-modal>
    </div>
</template>
<script>
import detail from "./detail";
const columns = [
    {
        title: "Doc",
        dataIndex: "properties.doc",
        key: "doc"
    },
    {
        title: "Doc/Action/Type",
        // dataIndex: "properties.subject",
        key: "subject",
        scopedSlots: { customRender: "docType" }
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
    components: {
        detail
    },
    props: {
        data: { default: () => [] }
    },

    data() {
        return {
            visible: false,
            columns,
            tracking_id: null,
            subject: null
        };
    },
    methods: {
        showModal(show) {
            this.visible = show;
        },
        detail(text, record) {
            this.subject = text.properties.subject;
            this.tracking_id = text.id;
            this.showModal(true);
        }
    },
    mounted() {}
};
</script>
