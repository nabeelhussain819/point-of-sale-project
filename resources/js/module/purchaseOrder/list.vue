<template>
    <div>
        <a-table
            :columns="columns"
            :data-source="data"
            bordered
            :loading="loading"
            :pagination="pagination"
        >
            <template slot="title">
                <strong> Purchase order list</strong>
            </template>
            <span slot="action" slot-scope="text, record">
                <span v-if="!record.received_at">
                    <a-button @click="goto(record)" type="primary"
                        >Receive
                    </a-button>
                    <a-button
                        @click="destroy(record)"
                        type="danger"
                        htmlType="button"
                        >delete
                    </a-button>
                </span>
                <span v-else>
                    <a-button @click="goto(record)" type="primary"
                        >Show
                    </a-button>
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
import { isEmpty, notification } from "../../services/helpers";
import pagination from "../../mixins/pagination";

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
    mixins: [pagination],
    data() {
        return {
            columns,
            data: [],
            loading: false
        };
    },
    mounted() {
        this.fetch();
        this.$emit("getFetch", this.fetch);
    },
    methods: {
        moment,
        isEmpty,
        fetch(params = {}) {
            this.loading = true;
            PurchaseOrderServices.all({ ...params, ...this.pagination })
                .then(response => {
                    console.log(response);
                    this.data = response.data;
                    this.setPagination(response);
                })
                .finally(() => (this.loading = false));
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
        },
        destroy(record) {
            PurchaseOrderServices.destroy(record.id).then(response => {
                notification(this, response.message);
                this.fetch();
            });
        }
    }
};
</script>
