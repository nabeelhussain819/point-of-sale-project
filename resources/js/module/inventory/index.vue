<template>
    <a-card title="Inventory">
        <list @showSerialNumber="showSerialNumber" />
        <a-modal title="Serial Number" :visible="visible" @ok="handleOk">
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
        showSerialNumber(product_id) {
            this.showModal(true);
            this.selectedProduct = { id: product_id };
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
