<template>
    <div>
        <a-form-item
            class="product_search"
            placeholder="insert id and press enter"
            :validate-status="fetchProductsErrors.validateStatus"
            :help="fetchProductsErrors.errorMsg"
            :label="showLabel && 'Scan Product Number'"
        >
            <a-input-search
                :loading="loading"
                @pressEnter="getProductById"
                placeholder="Insert search key"
                v-decorator="[
                    formName,
                    {
                        rules: rules
                    }
                ]"
                @blur="removeError"
                @search="getProductById"
            >
                <a-button type="primary" slot="enterButton">
                    <a-icon type="mobile" />
                </a-button>
            </a-input-search>
        </a-form-item>
    </div>
</template>
<script>
import InventoryService from "../../services/API/InventoryService";
import { isEmpty } from "../../services/helpers";
import { EVENT_CUSTOMERSALE_PRODUCT_ADD } from "../../services/constants";
export default {
    props: {
        appendData: {
            default: () => {}
        },
        formName: {
            default: "product_id"
        },
        showLabel: {
            default: true
        },
        rules: {
            default: () => []
        },
        notInventory: {
            default: false
        }
    },
    data() {
        return {
            loading: false,
            fetchProductsErrors: {},
            customer: null
        };
    },
    methods: {
        removeError() {
            this.fetchProductsErrors = {
                validateStatus: "",
                errorMsg: ""
            };
        },
        getProductById(e) {
            !isEmpty(e.target) && e.preventDefault();
            this.resetValidation();
            let productId = isEmpty(e.target) ? e : e.target.value;
            this.loading = true;
            let notInventory = this.notInventory;
            InventoryService.products({
                product_id: productId,
                OrUPC: productId,
                notInventory
            })
                .then(inventory => {
                    this.noProductFound(inventory);
                    if (!isEmpty(inventory)) {
                        let responseProduct = inventory.product;
                        if (notInventory) {
                            responseProduct = inventory;
                        }
                        responseProduct.appendData = this.appendData;
                        this.$eventBus.$emit(
                            EVENT_CUSTOMERSALE_PRODUCT_ADD,
                            responseProduct
                        );
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
                })
                .finally(() => (this.loading = false));
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
    },
    beforeDestroy() {
        // window.removeEventListener(EVENT_CUSTOMERSALE_PRODUCT_ADD, "asd");
    }
};
</script>
