<template>
    <div>
        <Serials
            v-if="showSerial"
            @onSelect="onSelect"
            @onSelectAll="onSelectAll"
            :product="product"
        />
        <a-form :form="form" @submit="handleSubmit" layout="inline">
            <formfields @onClose="onClose" :inventory="inventory" />
        </a-form>
    </div>
</template>

<script>
import Serials from "./../../product/serials";
import formfields from "./form-fields";
import InventoryService from "../../../services/API/InventoryService";
import { errorNotification, notification } from "../../../services/helpers";
export default {
    props: {
        inventory: { default: () => {} }
        // product: { default: () => {} }
    },
    data() {
        return {
            form: this.$form.createForm(this, { name: "binTransfer" }),
            showSerial: false,
            product: {},
            serials: []
        };
    },
    components: {
        Serials,
        formfields
    },
    methods: {
        onSelect(data) {
            if (data.isSelected) {
                // stateSerials.push(data.allSelected);
                this.serials = data.allSelected;
                return true;
            } else {
                console.error("not implemented");
                return false;
                stateSerials = stateSerials
                    .filter(serial => serial.id !== data.selected.id)
                    .map(serial => serial);
                this.serials = stateSerials;
                return true;
            }
        },
        onSelectAll(data) {
            if (data.selected) {
                this.serials = data.record;
                return true;
            }
            this.serials = [];
        },
        onClose(show) {
            this.$emit("onClose", show);
        },
        handleSubmit(e) {
            e.preventDefault();

            this.form.validateFields((err, values) => {
                //      validate if serail number should be allow only

                if (this.showSerial) {
                    if (values.quantity > this.serials.length) {
                        this.$error({
                            title: "Error",
                            content: "Please match the serial quantity"
                        });
                        return false;
                    }
                }

                if (!err) {
                    InventoryService.changeBin(this.inventory.id, {
                        ...values,
                        serial_numbers: this.serials
                    })
                        .then(inventory => {
                            this.$emit("onClose", false);
                            notification(this, inventory.message);
                        })
                        .catch(error => errorNotification(this, error));
                }
            });
        }
    },
    mounted() {
        this.product = {
            id: this.inventory.product.id,
            stock_bin_id: this.inventory.bin.id
        };
        this.showSerial = this.inventory.product.has_serial_number;
    }
};
</script>
