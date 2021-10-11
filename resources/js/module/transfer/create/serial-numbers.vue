<template>
    <div>
        <serials :product="stateProduct" @onSelect="onSelect" v-if="show" />
        <a-button type="primary" @click="complete">Complete</a-button>
    </div>
</template>
<script>
import serials from "./../../product/serials";
export default {
    components: {
        serials
    },
    data() {
        return {
            stateProduct: {},
            show: false,
            serials: {}
        };
    },
    props: {
        product: {
            default: () => {}
        }
    },
    mounted() {
        this.stateProduct = { id: this.product.product_id };
        this.show = true;
    },
    methods: {
        onSelect(data) {
            console.log(data);
            let serials = {
                key: this.product.key,
                product_id: this.product.product_id,
                serials_number: data.map(serial => serial.serial_no)
            };
            //     console.log("here data ", this.product, data);
            this.serials = serials;
        },
        complete() {
            this.$emit("close", this.serials);
        }
    }
};
</script>
