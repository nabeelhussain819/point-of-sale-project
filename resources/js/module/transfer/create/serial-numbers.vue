<template>
    <div>
        <serials :params="params" :product="stateProduct" @onSelect="onSelect" v-if="show" />
        <a-button type="primary" @click="complete">Complete</a-button>
    </div>
</template>
<script>
import serials from "./../../product/serials";
export default {
    components: {
        serials,
    },
    data() {
        return {
            stateProduct: {},
            show: false,
            serials: {},
        };
    },
    props: {
        product: {
            default: () => { },
        },
        params: { default: () => { } },
    },
    mounted() {
        this.stateProduct = { id: this.product.product_id };
        this.show = true;
    },
    methods: {
        onSelect(data) {

            let serials = {
                key: this.product.key,
                product_id: this.product.product_id,
                serials_number: data.allSelected.map(
                    (serial) => serial.serial_no
                ),
            };
            //     console.log("here data ", this.product, data);
            this.serials = serials;
        },
        complete() {
            this.$emit("close", this.serials);
        },
    },
};
</script>
