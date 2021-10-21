<template>
    <div>
        <a-descriptions bordered>
            <a-descriptions-item :span="24" label="Cost">
                <a-form-item>
                    <a-input
                        prefix="$"
                        type="number"
                        v-on:change="handleTotal"
                        v-decorator="[
                            'total',
                            {
                                rules: [
                                    {
                                        required: true,
                                        message: 'Please insert Cost!'
                                    }
                                ]
                            }
                        ]"
                /></a-form-item>
            </a-descriptions-item>
            <a-descriptions-item :span="24" label="Deposit">
                <a-form-item>
                    <a-input
                        :max="maxDeposite"
                        :min="0"
                        :disabled="enableDeposite"
                        @change="handleAdvance"
                        prefix="$"
                        type="number"
                        v-decorator="[
                            'advance',
                            {
                                rules: [
                                    {
                                        required: true,
                                        message: 'Please insert Deposit!'
                                    }
                                ]
                            }
                        ]"
                /></a-form-item>
            </a-descriptions-item>
            <a-descriptions-item :span="24" label="Balance">
                <a-form-item>
                    <a-input
                        :disabled="true"
                        prefix="$"
                        type="number"
                        v-decorator="[
                            'payable',
                            {
                                rules: [
                                    {
                                        required: true,
                                        message: 'Please insert Deposit!'
                                    }
                                ]
                            }
                        ]"
                /></a-form-item>
            </a-descriptions-item>
            <a-descriptions-item :span="24" label="Pay By card">
                <a-form-item>
                    
                    <a-switch
                        prefix="$"                      
                        v-decorator="[
                            'pay_by_card',
                            {
                                rules: []
                            }
                        ]"
                /></a-form-item>
            </a-descriptions-item>
        </a-descriptions>
    </div>
</template>
<script>
export default {
    props: {
        product: {
            default: () => {}
        },
        form: {
            default: () => {}
        },
        enableDeposite: {
            default: true
        }
    },
    data() {
        return {
            maxDeposite: 12,
            billSummary: {},
            advanceDisabled: true
        };
    },
    methods: {
        handleAdvance(value) {
            let formValues = this.form.getFieldsValue();
            this.maxDeposite = parseFloat(formValues.total);
            this.form.setFieldsValue({
                payable: parseFloat(formValues.total) - value.target.value
            });
        },
        handleTotal(value) {}
    }
};
</script>
<style scoped>
.ant-form-item {
    margin: 0 !important;
}
</style>
