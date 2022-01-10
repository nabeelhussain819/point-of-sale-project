<template>
    <div class="sales">
        <a-card :bordered="false">
            <div class="product-detail">
                <header-feature />
                <a-divider orientation="left"> Shopping List </a-divider>
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
        <a-alert type="info" show-icon message=" STORE POLICY">
            <div slot="description">
                <ol>
                    <li>
                        Products Phone, IPads, tablets, modems, watches,
                        bluetooth devices and other gadgets These products (if
                        unopened, no account created, and no sim inserted) are
                        refundable within seven days, however, a restocking fee
                        of 25% (or a fee of $150, whichever is greater) will
                        apply. Items need to be exchanged or returned in the
                        original condition. Items that are damaged, unsanitary,
                        dented, scratched or missing major contents may be
                        denied an exchange or return.
                    </li>
                    <li>
                        Accessories Non refundable, can be exchanged within
                        seven days. Must be in original condition and requires
                        the original packaging. Further policies are posted on
                        the counter For any questions or concerns, contact the
                        general manager <br />
                        <strong
                            >(see below for details) 703- 743-4384
                            aminali727@yahoo.com</strong
                        >
                    </li>
                </ol>
            </div></a-alert
        >
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

<style lang="scss">
.sales {
    .ant-input-group-addon {
        line-height: 0;
    }
    .anticon {
        vertical-align: top;
    }
    .anticon-user-add {
        font-size: 24px;
    }
}
</style>
