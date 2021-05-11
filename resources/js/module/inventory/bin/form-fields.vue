<template>
    <div>
        <a-form :form="form"  @submit="handleSubmit" layout="inline">
            <a-form-item label="Quantity">
                <a-input-number
                    :max="inventory.quantity"
                    :min="0"
                    v-decorator="[
                        `quantity`,
                        {
                            rules: [
                                {
                                    required: true,
                                    message: 'Please insert Quantity'
                                }
                            ]
                        }
                    ]"
                ></a-input-number>
            </a-form-item>

            <a-form-item label="Return To">
                <a-select
                    style="width: 120px"
                    v-decorator="[
                        `return_to`,
                        {
                            rules: [
                                {
                                    required: true,
                                    message: 'Please insert return to!'
                                }
                            ]
                        }
                    ]"
                    placeholder="Select a option and change input text above"
                >
                    <a-select-option v-for="type in returnTo" :key="type.id">
                        {{ type.name }}</a-select-option
                    >
                </a-select>
            </a-form-item>

            <a-form-item label="Bins">
                <a-select
                    style="width: 120px"
                    v-decorator="[
                        `stock_bin_id`,
                        {
                            rules: [
                                {
                                    required: true,
                                    message: 'Please insert Bin!'
                                }
                            ]
                        }
                    ]"
                    placeholder="Select a option and change input text above"
                >
                    <a-select-option v-for="stock in stocks" :key="stock.id">
                        {{ stock.name }}</a-select-option
                    >
                </a-select>
            </a-form-item>
            <a-form-item>
                <a-button type="primary" :loading="loading" htmlType="submit"
                    >Submit</a-button
                >
            </a-form-item>
        </a-form>
    </div>
</template>

<script>
import StockBinService from "../../../services/API/StockBinService";
export default {
    props: {
        inventory: { default: () => {} }
        // product: { default: () => {} }
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
                    id: 2,
                    name: "Bins"
                }
            ]
        };
    },
    methods: {
        getStock() {
            StockBinService.get().then(stocks => {
                this.stocks = stocks;
            });
        },
        handleSubmit(e) {
            e.preventDefault();
            this.form.validateFields((err, values) => {
                if (!err) {
                }
            });
        }
    },
    mounted() {
        this.getStock();
    }
};
</script>
