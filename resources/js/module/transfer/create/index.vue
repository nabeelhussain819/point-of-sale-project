<template>
    <div>
        <a-form
            :form="form"
            :label-col="{ span: 24 }"
            :wrapper-col="{ span: 24 }"
            @submit="handleSubmit"
        >
            <a-row :gutter="24">
                <a-col :span="8">
                    <a-form-item label="Start Date"
                        ><a-date-picker
                            class="w-100"
                            v-decorator="[
                                'transfer_date',
                                {
                                    rules: [{ required: true }]
                                }
                            ]"
                        />
                    </a-form-item>
                </a-col>
                <a-col :span="8">
                    <a-form-item label="Store Out">
                        <a-select
                            v-decorator="[
                                'store_in_id',
                                {
                                    rules: [{ required: true }]
                                }
                            ]"
                        >
                            <a-select-option
                                v-for="store in stores"
                                :key="store.id"
                                >{{ store.name }}</a-select-option
                            >
                        </a-select>
                    </a-form-item>
                </a-col>
                <a-col :span="8">
                    <a-form-item label="Store Out">
                        <a-select
                            v-decorator="[
                                'store_out_id',
                                {
                                    rules: [{ required: true }]
                                }
                            ]"
                        >
                            <a-select-option
                                v-for="store in stores"
                                :key="store.id"
                                >{{ store.name }}</a-select-option
                            >
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
            <products @close="getProductsWithSerials" />
            <a-alert
                banner
                :message="error.message"
                v-if="error.show"
            ></a-alert>
            <a-button type="primary" htmlType="submit">Submit</a-button>
        </a-form>
    </div>
</template>
<script>
import StoreService from "./../../../services/API/StoreService";
import TransferServices from "./../../../services/API/TransferServices";
import products from "./products";
export default {
    components: { products },
    data() {
        return {
            error: {
                message: null,
                show: false
            },
            formLayout: "horizontal",
            form: this.$form.createForm(this, { name: "addRepair" }),
            stores: []
        };
    },
    mounted() {
        this.fetchStore();
    },
    methods: {
        handleSubmit(e) {
            e.preventDefault();
            this.form.validateFields((err, values) => {
                const products = values.products;
                const serialQuantityCheck = this.validateQuantityAndSerial(
                    products
                );
                console.log(serialQuantityCheck);
                if (!err && serialQuantityCheck) {
                    this.saveTrasnfer(values);
                }
            });
        },
        saveTrasnfer(values) {
            TransferServices.store(values).then(data => {
                console.log(data);
            });
        },
        validateQuantityAndSerial(products) {
            for (const product in products) {
                let item = products[product];

                if (!item.has_serials) {
                    return true;
                }
                console.log(parseInt(item.quantity) !== item.serials.length);
                if (parseInt(item.quantity) !== item.serials.length) {
                    this.insertError(true, "please adjust the quantity");
                    return false;
                }
            }
            return true;
        },
        insertError(show, message) {
            this.error = {
                message,
                show
            };
        },
        fetchStore() {
            StoreService.get().then(response => {
                this.stores = response;
            });
        },
        getProductsWithSerials(data) {
            // console.log("getProductsWithSerials", data);
        }
    }
};

/* things todo 
// Showing product name 
// Remove product once seleced
// Quantity Validation how much stock avaiblabe on inventory
//
*/
</script>
