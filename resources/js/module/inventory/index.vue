<template>
    <a-card title="Inventory">
        <list @showSerialNumber="showSerialNumber" />
        <a-modal
            :destroyOnClose="true"
            title="Serial Number"
            :visible="visible"
            @ok="showModal(false)"
           @cancel="showModal(false)"
        >
            <serials :product="selectedProduct" />
        </a-modal>
    </a-card>
</template>

<script>
import List from "./list";
import Serials from "./../product/serials";
export default {
    components: { List, Serials },
    data() {
        return {
            selectedProduct: {},
            product_id: null,
            visible: false
        };
    },
    methods: {
        showSerialNumber(invenotry) {
            this.showModal(true);
            this.selectedProduct = {
                id: invenotry.product.id,
                stock_bin_id: invenotry.bin.id
            };
        },
        showModal(visible) {
            this.visible = visible;
        },
        handleOk() {
            this.showModal(false);
        }
    }
};
</script>
