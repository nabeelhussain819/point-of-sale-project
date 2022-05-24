<template>
    <div>
        <a-input-search placeholder="Insert search key" v-model="searchQuery">
        </a-input-search>
        <div v-for="r of resultQuery" :key="r.id">{{ r.title }}</div>

        <a-table :dataSource="data" :columns="columns">
            <span slot="action" slot-scope="text, record">
                <a-button v-on:click="showSerials(record)" type="link">
                    <a-icon type="appstore" theme="filled" />
                </a-button>
            </span>
        </a-table>

        <a-modal width="900" :footer="null" :visible="showSerialModal" title="Transfer modal"
            @cancel="handleSerialModal(false)" :destroyOnClose="true">
            <transfer v-if="!isEmpty(refundedVendor)" :record="refundedVendor" @closeModal="closeModalOnUpdate" />
        </a-modal>
    </div>
</template>
<script>
import VendorService from "../../services/API/VendorService";
import { isEmpty } from "../../services/helpers";
import transfer from "./transfer";


export default {
    components: { transfer },

    data() {
        return {
            searchQuery: "",
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
                {
                    title: "Action",
                    scopedSlots: { customRender: "action" },
                },
            ],
            showSerialModal: false,
            refundedVendor: null,
        };
    },
    mounted() {
        this.fetchRefund();
    },
    // Kia krna hy ?
    // search filter lgaya hy pgsql medata import krna hy ta k data ay
    // Ok
    // table konsa hy ?
    methods: {
        resultQuery() {
            if (!isEmpty(value)) {
                return this.data.filter(item => {
                    return this.searchQuery
                        .toLowerCase()
                        .split(" ")
                        .every(v => item.title.toLowerCase().includes(v));
                });
            } else {
                return this.data
            }
        },
        fetchRefund() {
            VendorService.getRefundedList().then((response) => {
                this.data = response.data;
            });
        },
        showSerials(refundedVendor) {
            console.log(refundedVendor);
            this.refundedVendor = refundedVendor;
            this.handleSerialModal(true);
        },
        handleSerialModal(show) {
            this.showSerialModal = show;
        },
        isEmpty,
        closeModalOnUpdate() {
            this.handleSerialModal(false);
            this.fetchRefund();
        }
    },
};
</script>
