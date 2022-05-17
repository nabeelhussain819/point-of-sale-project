<template>
    <div>
        <a-table :dataSource="data" :columns="columns">
            <span slot="action" slot-scope="text, record">
                <a-button v-on:click="showSerials(record)" type="link"
                    ><a-icon type="appstore" theme="filled" />
                </a-button>
            </span>
        </a-table>
        <a-modal
            width="900"
            :footer="null"
            :visible="showSerialModal"
            title="Transfer modal"
            @cancel="handleSerialModal(false)"
            :destroyOnClose="true"
        >
            <transfer
                v-if="!isEmpty(refundedVendor)"
                :record="refundedVendor"
                @closeModal="closeModalOnUpdate"
            />
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
    methods: {
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
        closeModalOnUpdate(){
            this.handleSerialModal(false);
            this.fetchRefund();
        }
    },
};
</script>
