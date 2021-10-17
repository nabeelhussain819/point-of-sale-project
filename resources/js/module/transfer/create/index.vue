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
                            v-if="showStore"
                            @change="e => getStores(e, 'out')"
                            v-decorator="[
                                'store_out_id',
                                {
                                    rules: [{ required: true }]
                                }
                            ]"
                        >
                            <a-select-option
                                v-for="store in store_out_id"
                                :key="store.id"
                                >{{ store.name }}</a-select-option
                            >
                        </a-select>
                    </a-form-item>
                </a-col>
                <a-col :span="8">
                    <a-form-item label="Store Out">
                        <a-select
                            @change="e => getStores(e, 'in')"
                            v-if="showStore"
                            v-decorator="[
                                'store_in_id',
                                {
                                    rules: [{ required: true }]
                                }
                            ]"
                        >
                            <a-select-option
                                v-for="store in store_in_id"
                                :key="store.id"
                                >{{ store.name }}</a-select-option
                            >
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
            <products
                v-if="storeOutSelected"
                @close="getProductsWithSerials"
                :form="form"
            />
            <a-alert
                banner
                :message="error.message"
                v-if="error.show"
            ></a-alert>

            <span>
                <a-alert
                    banner
                    message="Please select the store "
                    v-if="!storeOutSelected"
                >
                </a-alert>
                <a-button v-else type="primary" htmlType="submit"
                    >Submit</a-button
                >
            </span>
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
            stores: [],
            store_in_id: null,
            store_out_id: null,
            showStore: false,
            storeOutSelected: false
        };
    },
    mounted() {
        this.fetchStore();
    },
    methods: {
        getStores(e, type) {
            if (type === "out") {
                this.storeOutSelected = true;
                this.store_in_id = this.stores.filter(s => s.id !== e);
                return true;
            }
            this.store_in_out = this.stores.filter(s => s.id !== e);
            return true;
        },
        handleSubmit(e) {
            e.preventDefault();
            this.form.validateFields((err, values) => {
                const products = values.products;
                const serialQuantityCheck = this.validateQuantityAndSerial(
                    products
                );

                if (!err && serialQuantityCheck) {
                    this.saveTrasnfer(values);
                }
            });
        },
        saveTrasnfer(values) {
            TransferServices.store(values).then(data => {
                this.$emit("close", false);
            });
        },
        validateQuantityAndSerial(products) {
            for (const product in products) {
                let item = products[product];

                if (!item.has_serials) {
                    return true;
                }

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
                this.showStore = true;
                this.store_out_id = this.stores;
                this.store_in_id = this.stores;
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
