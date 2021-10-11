<template>
    <serials :product="stateProduct" @onSelect="onSelect" v-if="show" />
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
            show: false
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
        }
    }
};
</script>
