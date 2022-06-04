<template>
    <a-row :gutter="16">
        <a-form :form="form" :label-col="{ span: 24 }" :wrapper-col="{ span: 24 }" @submit="handleSubmit">
            <!-- <a-divider orientation="left">Customer Detail</a-divider> -->
            <customer-lookup :form="form" />

            <!-- <a-divider orientation="left">Finance & Card Detail</a-divider> -->
            <a-col :span="6">
                <a-form-item label="Type">
                    <a-select v-decorator="[
                        `type`,
                        {
                            rules: [
                                {
                                    required: true,
                                    message: 'Please insert type!'
                                }
                            ]
                        }
                    ]" placeholder="Select a option and change input text above">
                        <a-select-option v-for="type in types" :key="type.id">
                            {{ type.name }}</a-select-option>
                    </a-select>
                </a-form-item>
            </a-col>
            <credit-card-detail />
            <product-form-field @getProduct="getProduct" :form="form" />
            <a-col :span="24">
                <a-col :span="16">
                    <upload @getUpload="getUpload" />
                </a-col>
                <a-col :span="8">
                    <Summary :form="form" :enableDeposite="enableDeposite" :product="selectedProduct" />

                    <a-button type="primary" :loading="loading" htmlType="submit">Submit</a-button>
                </a-col>
            </a-col>
        </a-form>
    </a-row>
</template>
<script>
import Summary from "./summary";
import CustomerLookup from "../customer/basic-form-field";
import CreditCardDetail from "./../../components/FormFields/credit-card-detail";
import ProductFormField from "./product-form-field";
import upload from "./upload";
import { FINANCE_TYPE } from "../../services/constants";
import FinanceService from "../../services/API/FinanceService";
import {
    isEmpty,
    notification,
    errorNotification
} from "../../services/helpers";
export default {
    props: {
        finance: { default: () => { } }
    },
    data() {
        return {
            formLayout: "horizontal",
            form: this.$form.createForm(this, { name: "addFinance" }),
            customerDataSource: [],
            customerSearchLoading: false,
            types: FINANCE_TYPE,
            selectedProduct: {},
            billSummary: {},
            enableDeposite: true,
            loading: false,
            fileList: []
        };
    },
    methods: {
        handleSubmit(e) {
            e.preventDefault();
            this.form.validateFields((err, values) => {
                console.log(values)
                if (!err) {
                    FinanceService.create(values)
                        .then(response => {
                            notification(this, response.message);
                            return response;
                        })
                        .then(response => {
                            this.uploadAttachments(response);
                            this.show(false);
                        })
                        .catch(error => {
                            errorNotification(this, error);
                        })
                        .finally(() => {
                            this.loading;
                        });
                }
            });
        },
        getProduct(selectedProduct) {
            this.form.setFieldsValue({
                total: selectedProduct.product.retail_price
            });
            this.enableDeposite = false;
            this.selectedProduct = selectedProduct;
        },
        show(show) {
            this.$emit("onClose", show);
            this.visible = show;
        },
        getUpload(fileList) {
            this.fileList = fileList;
        },
        uploadAttachments(response) {

            if (!isEmpty(this.fileList)) {
                const formData = new FormData();
                this.fileList.forEach(file => {
                    formData.append("files[]", file);
                });
                FinanceService.uploads(response.finance.id, formData);
            }
        }
    },
    components: {
        CustomerLookup,
        CreditCardDetail,
        ProductFormField,
        Summary,
        upload
    }
};
</script>
