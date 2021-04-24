<template>
    <div>
        <a-descriptions bordered title="Bill summary">
            <a-descriptions-item :span="24" label="Without Tax">
                $ {{ billSummary.without_tax }}
            </a-descriptions-item>
            <a-descriptions-item :span="24" label="Total Discount">
                $ {{ billSummary.discount }}
            </a-descriptions-item>
            <!-- <a-descriptions-item :span="24" label="Without Discount">
            ${{ billSummary.withoutDiscount }} 
          </a-descriptions-item> -->
            <a-descriptions-item :span="24" label="Tax">
                {{ billSummary.tax }} %
            </a-descriptions-item>

            <a-descriptions-item label="Sub Total" :span="24">
                $ {{ billSummary.sub_total }}
            </a-descriptions-item>
            <a-descriptions-item label="Return Amount" :span="24">
                <a-form-item class="margin-0">
                    $
                    <a-input-number
                        width="100"
                        :max="parseFloat(billSummary.sub_total)"
                        type="number"
                        v-decorator="[
                            'return_cost',
                            {
                                rules: [
                                    {
                                        required: true,
                                        message: 'Please insert return number!'
                                    }
                                ]
                            }
                        ]"
                    />
                </a-form-item>
            </a-descriptions-item>
            <a-descriptions-item label="Reason For Return" :span="24">
                <a-form-item class="margin-0">
                    <a-input v-decorator="['reason']" />
                </a-form-item>
            </a-descriptions-item>
        </a-descriptions>
    </div>
</template>
<script>
export default {
    data() {
        return {
            billSummary: {}
        };
    },
    components: {},
    computed: {},
    props: { order: { default: () => {} } },
    mounted() {
        this.billSummary = this.order;
    }
};
</script>
