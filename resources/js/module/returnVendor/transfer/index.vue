<template>
    <div>
        <Serials
            v-if="record.has_serial_number"
            :product="{ id: record.product_id }"
            :params="{ vendor_id: record.vendor.id, return_to_vendor: true }"
            @onSelect="onSelect"
        />
        <a-form :form="form" @submit="handleSubmit" layout="inline">
            <a-form-item label="Quantity">
                <a-input-number
                    :max="record.quantity"
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
                <a-input
                    type="hidden"
                    v-decorator="[
                        `vendor_refund_id`,
                        {
                            initialValue: record.id,
                            rules: [
                                {
                                    required: true,
                                    message: 'Please insert Quantity',
                                },
                            ],
                        },
                    ]"
                ></a-input>
            </a-form-item>
            <a-alert
                type="error"
                :message="error.message"
                v-if="error.status"
            ></a-alert>
            <a-form-item>
                <a-button type="primary" :loading="loading" htmlType="submit"
                    >Submit</a-button
                >
            </a-form-item>
        </a-form>
    </div>
</template>
<script>
import Serials from "./../../product/serials";
import InventoryService from "../../../services/API/InventoryService";
export default {
    props: {
        record: { type: Object },
    },
    data() {
        return {
            form: this.$form.createForm(this, { name: "vendorRefund" }),
            serialNumber: {},
            loading: false,
            error: {},
        };
    },
    components: { Serials },
    methods: {
        onSelect(value) {
            console.log(value)
            this.serialNumber = value.allSelected.map(s=>s.serial_no);
        },
        handleSubmit(e) {
            e.preventDefault();
            this.loading = true;
            this.form.validateFields((err, values) => {
                this.error = {};
                if (!err) {
                    if (this.validateSerialsNumbers(values)) {
                        InventoryService.getBackFromVendor(this.record.id, {
                            ...values,
                            serial_numbers: this.serialNumber,
                        }).then(()=>{
                            this.$emit("closeModal");
                        });
                    } else {
                        this.error = {
                            message: "match the quantity with Serials",
                            status: true,
                        };
                    }
                }
            });
            this.loading = false;
        },
        validateSerialsNumbers(values) {
            const refundedProduct = this.record;
            if (refundedProduct.has_serial_number) {
                const quantity = values.quantity;
                return quantity === this.serialNumber.length;
            }
            return true;
        },
    },
};
</script>
