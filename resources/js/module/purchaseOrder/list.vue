<template>
    <div>
        <a-table :columns="columns" :data-source="data" bordered>
            <template slot="title">
                <strong> Purchase order list</strong>
            </template>
            <span slot="action" slot-scope="text, record">
                <a-button
                    v-if="!record.received_at"
                    @click="goto(record)"
                    type="primary"
                    >Receive
                </a-button>
                <span v-else>
                    <a-icon
                        type="check-circle"
                        theme="twoTone"
                        two-tone-color="#52c41a"
                    />Received At {{ record.received_at }}</span
                >
            </span>
            <span slot="dateSearch">
                <a-range-picker
                    v-decorator="[
                        'dateTime',
                        {
                            initialValue: [
                                getPastMoment(365),
                                moment().set({ h: 11, m: 59 })
                            ]
                        }
                    ]"
                    :ranges="{
                        Today: [moment(), moment()],
                        Week: [getPastMoment(7), moment()],
                        Year: [getPastMoment(365), moment()],
                        Month: [getPastMoment(30), moment()]
                    }"
                    format="YYYY/MM/DD"
                    @change="dateRangeChange"
                />
            </span>
        </a-table>
    </div>
</template>

<script>
import PurchaseOrderServices from "../../services/API/PurchaseOrderServices";
import { isEmpty } from "../../services/helpers";
import moment from "moment";
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
        key: "created_date",
        scopedSlots: {
            filterDropdown: "dateSearch",
            filterIcon: "filterIcon"
        }
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
        moment,
        isEmpty,
        fetch(params = {}) {
            PurchaseOrderServices.all(params).then(response => {
                this.data = response.data;
            });
        },
        goto(item) {
            window.location.href = `/purchase-order/received-form/${item.id}`;
        },
        //move this into mixins
        getPastMoment(days) {
            return moment()
                .subtract(days, "days")
                .set({ h: 0, m: 0 });
        },
        dateRangeChange(dates, dateStrings) {
            this.params = {
                ...this.params,
                date_range: [
                    dates[0].set({ h: 0, m: 0 }).format("YYYY-MM-DDTHH:mm:ss"),
                    dates[1].set({ h: 23, m: 59 }).format("YYYY-MM-DDTHH:mm:ss")
                ]
            };
            this.fetch(this.params);
        }
    }
};
</script>
