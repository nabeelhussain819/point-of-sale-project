<template>
    <div>
        <a-skeleton :loading="loading">
            <a-card title="Purchase Order" :bordered="false">
                <a-form
                    v-if="showAdd"
                    :form="form"
                    @submit="handleSubmit"
                    layout="inline"
                >
                    <create />
                    <products-form-field />
                    <a-form-item
                        :validate-status="fetchProductsErrors.validateStatus"
                        :help="fetchProductsErrors.errorMsg"
                    >
                        <a-button
                            type="primary"
                            :loading="loading"
                            htmlType="submit"
                            >Submit</a-button
                        >
                    </a-form-item>
                </a-form>
            </a-card>
        </a-skeleton>
        <List @getFetch="getFetch" />
    </div>
</template>
<script>
import {
    isEmpty,
    notification,
    errorNotification
} from "../../services/helpers";
import create from "./create-form-field";
import List from "./list";
import ProductsFormField from "./products-form-fields";
import PurchaseOrderServices from "../../services/API/PurchaseOrderServices";
export default {
    props: {
        params: { type: Object, required: false, default: () => ({}) },
        showAdd: { type: Boolean, required: false, default: true }
    },
    components: { create, ProductsFormField, List },
    data() {
        return {
            fetchProductsErrors: {},
            loading: false,
            form: this.$form.createForm(this, { name: "purchaseOrder" }),
            products: {}
        };
    },
    methods: {
        errorHandler(messsage, type = "error") {
            this.fetchProductsErrors = {
                validateStatus: type,
                errorMsg: messsage
            };
        },
        handleSubmit(e) {
            e.preventDefault();
            let products = this.form.getFieldValue("products");
            if (isEmpty(products)) {
                this.errorHandler("Please aadd products in purchase form ");
                return false;
            }

            this.form.validateFields((err, values) => {
                if (!err) {
                    this.loading = true;
                    PurchaseOrderServices.create(values)
                        .then(response => {
                            notification(this, response.message);
                            location.reload();
                        })
                        .catch(error => {
                            errorNotification(this, error);
                        })
                        .finally(() => (this.loading = false));
                }
            });
        },
        getFetch(postedFunction) {
            this.$emit("getFetch", postedFunction);
        }
    },
    mounted() {}
};
</script>
