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
        };
    },
    components: { Serials },
    methods: {
        onSelect(value) {
            this.serialNumber = value.allSelected;
        },
        handleSubmit(e) {
            e.preventDefault();
            this.loading = true;
            this.form.validateFields((err, values) => {
                if (!err) {
                    console.log(values);

                    InventoryService.getBackFromVendor({
                        ...values,
                        serial_numbers: this.serialNumber,
                    });
                }
            });
            this.loading = false;
        },
    },
};
</script>
