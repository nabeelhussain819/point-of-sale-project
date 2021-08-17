<template>
    <div>
        <a-col :span="6">
            <a-form-item
                
                :validate-status="fetchProductsErrors.validateStatus"
                :help="fetchProductsErrors.errorMsg"
            >
                <AddProduct />
                <!-- <a-input-search
                    enter-button
                    v-decorator="[
                        'product_upc',
                        {
                            rules: [
                                {
                                    required: true,
                                    message: 'Please insert keys!'
                                }
                            ]
                        }
                    ]"
                    @search="getProductById"
                    type="text"/> -->
            </a-form-item></a-col
        >
        <a-col :span="6">
            <a-form-item label="Product Name">
                <a-input
                    :disabled="true"
                    v-decorator="['product_name']"
                    type="customer_number"
                />
                <a-input
                    :disabled="true"
                    v-decorator="['product_id']"
                    type="hidden"
                /> </a-form-item
        ></a-col>
        <a-col :span="6">
            <a-form-item label="Product Serial Number">
                <a-input
                    :disabled="!productHasSerial"
                    v-decorator="[
                        'serial_number',
                        {
                            rules: serialKeyRules
                        }
                    ]"/></a-form-item
        ></a-col>

        <a-col :span="6">
            <a-form-item label="Comments">
                <a-textarea :rows="1" v-decorator="['comments']"/></a-form-item
        ></a-col>
    </div>
</template>
<script>
import InventoryService from "../../services/API/InventoryService";
import { isEmpty } from "../../services/helpers";
import AddProduct from "../product/add";
import { EVENT_CUSTOMERSALE_PRODUCT_ADD } from "../../services/constants";
export default {
    components: {
        AddProduct
    },
    props: {
        form: {
            default: () => {}
        }
    },
    data() {
        return {
            fetchProductsErrors: {},
            customer: null,
            productHasSerial: true,
            serialKeyRules: []
        };
    },
    mounted() {
        this.setSerialRule(true);
        this.addProductDetail();
    },

    methods: {
        addProductDetail() {
            const getProductById = this.getProductById
            this.$eventBus.$on(EVENT_CUSTOMERSALE_PRODUCT_ADD, function(
                product
            ) {
                getProductById(product.id);
                console.log(product);
            });
        },
        getSerialRules(hasSerial) {
            if (hasSerial) {
                return [
                    {
                        required: true,
                        message: "Please insert serial!"
                    }
                ];
            }
            return [];
        },
        setSerialRule(hasSerial) {
            this.serialKeyRules = this.getSerialRules(hasSerial);
        },
        getProductById(e) {
            this.resetValidation();
            let productId = e;
            InventoryService.products({
                product_id: productId,
                OrUPC: productId
            })
                .then(inventory => {
                    this.noProductFound(inventory);

                    if (!isEmpty(inventory)) {
                        this.handleSerialNumber(inventory);
                        this.$emit("getProduct", inventory);
                        this.form.setFieldsValue({
                            product_name: inventory.product.name,
                            product_id: inventory.product.id,
                            serial_number: inventory.serial_number
                        });
                    }
                })
                .catch(ex => {
                    if (ex.response.status === 409) {
                        this.fetchProductsErrors = {
                            validateStatus: "error",
                            errorMsg: ex.response.data.message
                        };
                    }
                    if (ex.response.status === 404) {
                        this.fetchProductsErrors = {
                            validateStatus: "error",
                            errorMsg: "No Product Found Against Key"
                        };
                    }
                });
        },
        handleSerialNumber(inventory) {
            this.productHasSerial = inventory.product.has_serial_number;
            this.setSerialRule(this.productHasSerial);
        },
        noProductFound(data) {
            if (isEmpty(data)) {
                this.fetchProductsErrors = {
                    validateStatus: "error",
                    errorMsg: "No Product Found Against Key"
                };
            }
        },
        resetValidation() {
            this.fetchProductsErrors = {};
        }
    }
};
</script>
