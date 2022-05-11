<template>
    <div>
        <a-form-item label="Quantity">
            <a-input-number
                :max="inventory.quantity"
                :min="1"
                v-decorator="[
                    `quantity`,
                    {
                        rules: [
                            {
                                required: true,
                                message: 'Please insert Quantity',
                            },
                        ],
                    },
                ]"
            ></a-input-number>
        </a-form-item>

        <a-form-item label="Return To">
            <a-select
                style="width: 120px"
                @change="handleReturnTo"
                v-decorator="[
                    `return_to`,
                    {
                        rules: [
                            {
                                required: true,
                                message: 'Please insert return to!',
                            },
                        ],
                    },
                ]"
                placeholder="Select a option and change input text above"
            >
                <a-select-option v-for="type in returnTo" :key="type.id">
                    {{ type.name }}</a-select-option
                >
            </a-select>
        </a-form-item>

        <a-form-item v-if="!isVendor" label="Bins">
            <a-select
                style="width: 120px"
                v-decorator="[
                    `stock_bin_id`,
                    {
                        rules: [
                            {
                                required: true,
                                message: 'Please insert Bin!',
                            },
                        ],
                    },
                ]"
                placeholder="Select a option and change input text above"
            >
                <a-select-option v-for="stock in stocks" :key="stock.id">
                    {{ stock.name }}</a-select-option
                >
            </a-select>
        </a-form-item>
        <a-form-item v-else label="Vendors">
            <a-select
                style="width: 120px"
                v-decorator="[
                    `vendor_id`,
                    {
                        rules: [
                            {
                                required: true,
                                message: 'Please insert Vendor!',
                            },
                        ],
                    },
                ]"
                placeholder="Select a option and change input text above"
            >
                <a-select-option v-for="vendor in vendors" :key="vendor.id">
                    {{ vendor.name }}</a-select-option
                >
            </a-select>
        </a-form-item>

        <a-form-item>
            <a-button type="primary" :loading="loading" htmlType="submit"
                >Submit</a-button
            >
        </a-form-item>
    </div>
</template>

<script>
import StockBinService from "../../../services/API/StockBinService";
import VendorService from "../../../services/API/VendorService";
export default {
    props: {
        inventory: { default: () => {} },
    },
    data() {
        return {
            loading: false,
            stocks: {},
            form: this.$form.createForm(this, { name: "binTransfer" }),
            returnTo: [
                {
                    id: 1,
                    name: "Vendor",
                },
                { id: 2, name: "Bins" },
            ],
            isVendor: false,
            vendors: [],
        };
    },
    methods: {
        getStock() {
            StockBinService.get({
                exclude_id: this.inventory.stock_bin_id,
            }).then((stocks) => {
                this.stocks = stocks;
            });
        },
        handleReturnTo(value) {
            return (this.isVendor = value == 1);
        },
        fetch() {
            VendorService.getList().then((vendors) => {
                this.vendors = vendors;
            });
        },
    },
    mounted() {
        this.getStock();
        this.fetch();
    },
};
</script>
