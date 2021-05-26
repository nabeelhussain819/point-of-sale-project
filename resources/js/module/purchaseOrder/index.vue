<template>
    <a-card title="Purchase Order" :bordered="false">
        <a-form :form="form" @submit="handleSubmit" layout="inline">
            <create />
            <products-form-field />
            <a-form-item>
                <a-button type="primary" :loading="loading" htmlType="submit"
                    >Submit</a-button
                >
            </a-form-item>
        </a-form>
    </a-card>
</template>
<script>
import create from "./create-form-field";
import ProductsFormField from "./products-form-fields";
export default {
    components: { create, ProductsFormField },
    data() {
        return {
            loading: false,
            form: this.$form.createForm(this, { name: "binTransfer" })
        };
    },
    methods: {
        handleSubmit(e) {
            e.preventDefault();
            this.form.validateFields((err, values) => {
                if (!err) {
                    console.log(values);
                }
            });
        }
    }
};
</script>
