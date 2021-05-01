<template>
    <a-row :gutter="16">
        <a-col :span="11">
            <a-form-item
                placeholder="insert id and press enter"
                :validate-status="fetchProductsErrors.validateStatus"
                :help="fetchProductsErrors.errorMsg"
                label="Scan Product Number"
            >
                <a-input-search
                    @pressEnter="getProductById"
                    placeholder="Insert search key"
                    v-decorator="[
                        'product_id',
                        {
                            rules: rules
                        }
                    ]"
                    @search="getProductById"
                >
                    <a-button type="primary" slot="enterButton">
                        <a-icon type="mobile" />
                    </a-button>
                </a-input-search> </a-form-item
        ></a-col>
    </a-row>
</template>
<script>
import TrackingService from "../../services/API/TrackingService";
import { isEmpty } from "../../services/helpers";
export default {
    props: {
        rules: {
            default: () => []
        }
    },
    data() {
        return {
            fetchProductsErrors: {},
            customer: null
        };
    },
    methods: {
        getProductById(e) {
            this.resetValidation();
            let serial_number = isEmpty(e.target) ? e : e.target.value;
            TrackingService.get(serial_number)
                .then(product => {
                    // this.noProductFound(inventory);

                    this.$emit("getData", product);
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
        resetValidation() {
            this.fetchProductsErrors = {};
        }
    },
    beforeDestroy() {
        // window.removeEventListener(EVENT_CUSTOMERSALE_PRODUCT_ADD, "asd");
    }
};
</script>
