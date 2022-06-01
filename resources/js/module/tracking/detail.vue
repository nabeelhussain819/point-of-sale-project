<template>
    <a-skeleton :loading="loading">
        <a-descriptions>
            <!-- <a-descriptions-item label="Type">
                Zhou Maomao
            </a-descriptions-item> -->
            <a-descriptions-item label="Date Time">
                {{ this.date }}
            </a-descriptions-item>
        </a-descriptions>
        <a-table :columns="columns" :data-source="data"> </a-table>
    </a-skeleton>
</template>
<script>
import TrackingService from "../../services/API/TrackingService";
import { isEmpty } from "../../services/helpers";
const columns = [
    {
        title: "name",
        dataIndex: "product.name",
        key: "product.name"
    },
    {
        title: "quantity",
        dataIndex: "quantity",
        key: "quantity",
    }
];

export default {
    props: {
        tracking_id: {
            default: null
        },
        subject: {
            default: null
        },
        date: {
            default: null
        }
    },
    data() {
        return {
            loading: true,
            tracking: {},
            columns,
            data: [],
            isEmpty
        };
    },
    mounted() {

        if (!isEmpty(this.tracking_id)) {
            this.loading = true;

            TrackingService.show(this.tracking_id)
                .then(tracking => {
                    this.tracking = tracking;
                    this.data = this.tracking.products;
                })
                .finally(() => (this.loading = false));
        }
    }
};
</script>
