<template>
    <div>
        <Invoice :finance="stateFinance" />
        <br/>
        <a-card title="Payable">
            <a-form layout="inline" :form="form" @submit="handleSubmit">
                <a-form-item>
                    <a-input v-decorator="['comment']" placeholder="Comments">
                    </a-input>
                </a-form-item>
                <a-form-item>
                    <a-input
                        type="number"
                        v-decorator="[
                            'received_amount',
                            {
                                rules: [
                                    {
                                        required: true,
                                        message:
                                            'Please input your received amount!'
                                    }
                                ]
                            }
                        ]"
                        placeholder="Received Amount $"
                    >
                    </a-input>
                </a-form-item>
                <a-form-item :wrapper-col="{ span: 12, offset: 5 }">
                    <a-button type="primary" html-type="submit">
                        Submit
                    </a-button>
                </a-form-item>
            </a-form>
        </a-card>
    </div>
</template>
<script>
import Invoice from "./Invoice";
export default {
    components: { Invoice },
    data() {
        return {
            form: this.$form.createForm(this, { name: "addCustomer" }),
            stateFinance: {}
        };
    },
    props: {
        finance: {
            default: () => {}
        }
    },
    mounted() {
        this.stateFinance = this.finance;
    },
    methods: {
        handleSubmit(e) {
            e.preventDefault();
            this.form.validateFields((err, values) => {
                if (!err) {
                }
            });
        }
    }
};
</script>
