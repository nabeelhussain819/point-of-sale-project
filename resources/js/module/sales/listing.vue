<template>
    <div>
        <a-skeleton :loading="loading">
            <a-table
                class="condense-table table-bordered table-condensed"
                :columns="columns"
                :pagination="pagination"
                :data-source="data"
            >
                <template slot="title">
                    <a href="/sales/create">
                        <a-button type="primary">Create </a-button>
                    </a>
                </template>
                <template slot="total" slot-scope="text">
                    {{ text }}$
                </template>
                <a slot="name" slot-scope="text">{{ text }}</a>
                <span slot="customTitle"><a-icon type="smile-o" /> Name</span>

                <span slot="action" slot-scope="text, record">
                    <a-icon
                        v-on:click="showPrintModal(record.id)"
                        type="printer"
                    />
                    <a :href="href(record.id)">refund</a>
                </span>
                <div
                    slot="filterDropdown"
                    slot-scope="{ setSelectedKeys, selectedKeys, column }"
                    style="padding: 8px"
                >
                    <a-input
                        v-ant-ref="c => (searchInput = c)"
                        :placeholder="`Search ${column.dataIndex}`"
                        :value="selectedKeys[0]"
                        style="width: 188px; margin-bottom: 8px; display: block"
                        @change="
                            e =>
                                setSelectedKeys(
                                    e.target.value ? [e.target.value] : []
                                )
                        "
                        @pressEnter="() => handleSearch(selectedKeys, column)"
                    />
                    <a-button
                        type="primary"
                        icon="search"
                        size="small"
                        style="width: 90px; margin-right: 8px"
                        @click="() => handleSearch(selectedKeys, column)"
                    >
                        Search
                    </a-button>
                    <a-button
                        size="small"
                        style="width: 90px"
                        @click="() => handleReset(selectedKeys, column)"
                    >
                        Reset
                    </a-button>
                </div>
            </a-table>
        </a-skeleton>

        <a-modal
            :destroyOnClose="true"
            width="100%"
            :visible="showOrderInvoice"
            @cancel="toggleModal(false)"
            title=""
            footer=""
            ><product-table
                :createdOrder="order"
                :isCreated="true"
                @orderCreated="orderCreated"
                :products="products"
                :customer="customer"
                :billSummary="billSummary"
        /></a-modal>
    </div>
</template>

<script>
import OrderService from "../../services/API/OrderServices";
import productTable from "./products-table";
import { isEmpty } from "../../services/helpers";
import pagination from "../../mixins/pagination";

export default {
    mixins: [pagination],
    components: { productTable },
    name: "index.vue",
    data() {
        return {
            isEmpty,
            data: [],
            showOrderInvoice: false,
            columns: [
                {
                    dataIndex: "id",
                    key: "id",
                    title: "Invoice",
                    scopedSlots: {
                        filterDropdown: "filterDropdown",
                        filterIcon: "filterIcon"
                    }
                },
                {
                    dataIndex: "customer.name",
                    key: "customerName",
                    title: "Customer Name",
                    scopedSlots: {
                        filterDropdown: "filterDropdown",
                        filterIcon: "filterIcon"
                    }
                },
                {
                    dataIndex: "sub_total",
                    key: "sub_total",
                    title: "Total",
                    scopedSlots: { customRender: "total" }
                },
                {
                    title: "Customer Number",
                    dataIndex: "customer.phone",
                    key: "customerPhone",
                    scopedSlots: {
                        filterDropdown: "filterDropdown",
                        filterIcon: "filterIcon"
                    }
                },
                {
                    title: "Date",
                    dataIndex: "date",
                    key: "date"
                },
                {
                    title: "Action",
                    key: "action",
                    scopedSlots: { customRender: "action" }
                }
            ],
            loading: false,
            pagination: {},
            products: {},
            customer: {},
            billSummary: {},
            order: {},
            filters: {}
        };
    },
    mounted() {
        this.fetch();
        const urlSearchParams = new URLSearchParams(window.location.search);
        const params = Object.fromEntries(urlSearchParams.entries());
        console.log("params", params);
        this.showOrder();
    },
    methods: {
        showOrder() {
            const urlSearchParams = new URLSearchParams(window.location.search);
            const params = Object.fromEntries(urlSearchParams.entries());
            if (params.order_id) {
                this.showPrintModal(params.order_id);
            }
        },
        href(id) {
            return `refund/order/${id}`;
        },
        toggleModal(show) {
            this.showOrderInvoice = show;
        },
        orderCreated(created) {
            this.isOrderCreated = created;
        },
        fetch(params = {}) {
            this.loading = true;
            OrderService.all({ ...params, ...this.pagination })
                .then(data => {
                    this.data = data.data;
                    this.setPagination(data);
                })
                .finally(() => (this.loading = false));
        },
        showPrintModal(orderId) {
            OrderService.show(orderId).then(order => {
                this.customer = order.customer;
                this.products = order.products.map(product => {
                    product.name = product.product.name;
                    return product;
                });
                this.billSummary = {
                    discount: order.discount,
                    withoutTax: order.without_tax,
                    subTotal: order.sub_total,
                    withoutDiscount: order.without_discount,
                    tax: order.tax
                };
                this.toggleModal(true);
                this.order = order;
            });
        },
        setfilters(filters) {
            this.filters = JSON.parse(JSON.stringify(filters));
            this.fetch(this.filters);
        },
        handleSearch(value, column) {
            let filters = this.filters;
            filters[column.key] = value[0];
            this.setfilters(filters);
        },
        handleReset(value, column) {
            let filters = this.filters;
            delete filters[column.key];
            this.setfilters(filters);
        }
    }
};
</script>

<style scoped></style>
