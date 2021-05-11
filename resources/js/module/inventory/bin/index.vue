<template>
    <div>
        <Serials v-if="showSerial" :product="product" />
        <formfields :inventory="inventory" />
    </div>
</template>

<script>
import Serials from "./../../product/serials";
import formfields from "./form-fields";
export default {
    props: {
        inventory: { default: () => {} }
        // product: { default: () => {} }
    },
    data() {
        return {
            showSerial: false,
            product: {}
        };
    },
    components: {
        Serials,
        formfields
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
