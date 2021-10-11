<template>
    <div>
        <a-form
            :form="form"
            :label-col="{ span: 24 }"
            :wrapper-col="{ span: 24 }"
            @submit="handleSubmit"
        >
            <a-row :gutter="24">
                <a-col :span="8">
                    <a-form-item label="Start Date"
                        ><a-date-picker
                            class="w-100"
                            v-decorator="[
                                'expected_date',
                                {
                                    rules: [{ required: true }]
                                }
                            ]"
                        />
                    </a-form-item>
                </a-col>
                <a-col :span="8">
                    <a-form-item label="Store Out">
                        <a-select
                            v-decorator="[
                                'store_in',
                                {
                                    rules: [{ required: true }]
                                }
                            ]"
                        >
                            <a-select-option
                                v-for="store in stores"
                                :key="store.id"
                                >{{ store.name }}</a-select-option
                            >
                        </a-select>
                    </a-form-item>
                </a-col>
                <a-col :span="8">
                    <a-form-item label="Store Out">
                        <a-select
                            v-decorator="[
                                'store_out',
                                {
                                    rules: [{ required: true }]
                                }
                            ]"
                        >
                            <a-select-option
                                v-for="store in stores"
                                :key="store.id"
                                >{{ store.name }}</a-select-option
                            >
                        </a-select>
                    </a-form-item>
                </a-col>
            </a-row>
            <products />
            <a-button type="primary" htmlType="submit">Submit</a-button>
        </a-form>
    </div>
</template>
<script>
import StoreService from "./../../../services/API/StoreService";
import products from "./products";
export default {
    components: { products },
    data() {
        return {
            formLayout: "horizontal",
            form: this.$form.createForm(this, { name: "addRepair" }),
            stores: []
        };
    },
    mounted() {
        this.fetchStore();
    },
    methods: {
        handleSubmit(e) {
            e.preventDefault();
            this.form.validateFields((err, values) => {
                console.log(err, values);
                if (!err) {
                }
            });
        },
        fetchStore() {
            StoreService.get().then(response => {
                console.log(response);
                this.stores = response;
            });
        }
    }
};
</script>
