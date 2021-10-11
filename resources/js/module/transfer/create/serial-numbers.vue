<template>
    <span>{{ product }}</span>
</template>
<script>
export default {
    data() {
        return {};
    },
    props: {
        product: {
            default: () => {}
        }
    },
    mounted(){
        console.log(this.product);
    }
};
</script>
