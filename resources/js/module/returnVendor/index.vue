<template>
    <div>


        <a-table class="table table-bordered" :columns="columns" :data-source="data" :loading="loading">
            <div slot="filterDropdown" slot-scope="{
                setSelectedKeys,
                selectedKeys,

                column
            }" style="padding: 8px">
                <a-input v-ant-ref="c => (searchInput = c)" :placeholder="`Search ${column.dataIndex}`"
                    :value="selectedKeys[0]" style="width: 188px; margin-bottom: 8px; display: block" @change="
                        e => setSelectedKeys(e.target.value ? [e.target.value] : [])
                    " @pressEnter="() => handleSearch(selectedKeys, column)" />
                <a-button type="primary" icon="search" size="small" style="width: 90px; margin-right: 8px"
                    @click="() => handleSearch(selectedKeys, column)">
                    Search
                </a-button>
                <a-button size="small" style="width: 90px" @click="() => handleReset(selectedKeys, column)">
                    Reset
                </a-button>
            </div>
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
            data: [],
            loading: true,
            filters: {},
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
                    scopedSlots: {
                        filterDropdown: "filterDropdown",
                        filterIcon: "filterIcon"
                    }
                },
                {
                    dataIndex: "product.name",
                    key: "product",
                    title: "product",
                    scopedSlots: {
                        filterDropdown: "filterDropdown",
                        filterIcon: "filterIcon"
                    }
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
        this.fetch();
    },

    methods: {
        setfilters(filters) {
            this.filters = JSON.parse(JSON.stringify(filters));
            this.fetch(this.filters);
        },
        handleReset(value, column) {
            let filters = this.filters;
            delete filters[column.key];
            this.setfilters(filters);
        },
        handleSearch(value, column) {
            let filters = this.filters;
            filters[column.key] = value[0];
            this.setfilters(filters);
        },
        fetch(params = {}) {
            this.loading = true;
            VendorService.getRefundedList({ ...params }).then((response) => {
                this.data = response.data;
            }).finally(() => (this.loading = false));
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
            this.fetch();
        }
    },
};
</script>
