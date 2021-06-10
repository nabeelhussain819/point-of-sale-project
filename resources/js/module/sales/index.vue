<template>
    <div class="sales">
        <a-card :bordered="false">
            <div class="product-detail">
                <header-feature />
                <a-divider orientation="left"> Shoping List </a-divider>
                <a-form
                    :form="form"
                    :label-col="{ span: 24 }"
                    :wrapper-col="{ span: 24 }"
                    @submit="handleSubmit"
                >
                    <products
                        :form="form"
                        :preloadProduct="pre"
                        @updatedProducts="updatedProducts"
                    />
                </a-form>
                <a-divider orientation="left"> Order Summary </a-divider>
                <product-summary @handleSubmit="handleSubmit" />
            </div>
        </a-card>
    </div>
</template>
<script>
import headerFeature from "./header-feature";
import products from "./products";
import productSummary from "./product-summary";
export default {
    props: {
        pre: {
            default: () => {},
            type: Object
        }
    },
    data() {
        return {
            products: {},
            formLayout: "horizontal",
            form: this.$form.createForm(this, { name: "orders" })
        };
    },
    components: {
        headerFeature,
        products,
        productSummary
    },
    methods: {
        updatedProducts(products) {
            // console.log(products);
        },
        handleSubmit(callback) {
            this.form.validateFields((err, values) => {
                if (!err) {
                    callback(true);
                }
            });
        }
    },
    mounted() {
        
        // console.log("parent", this.pre);
    }
};
</script>
