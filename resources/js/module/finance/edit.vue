<template>
    <div>
        <a-row gutter="12">
            <a-col :span="16">
                <a-descriptions
                    bordered
                    :title="`${finance.product.name} Finance Detail`"
                >
                    <a-descriptions-item label="Customer Name">
                        {{ finance.customer.name }}
                    </a-descriptions-item>
                    <a-descriptions-item label="Telephone">
                        {{ finance.customer.phone }}
                    </a-descriptions-item>
                    <a-descriptions-item label="Address">
                        {{ finance.customer.phone }}
                    </a-descriptions-item>
                    <a-descriptions-item label="Item">
                        {{ finance.product.name }}
                    </a-descriptions-item>
                    <a-descriptions-item label="Total">
                        ${{ finance.total }}
                    </a-descriptions-item>
                    <a-descriptions-item label="Advance">
                        ${{ finance.advance }}
                    </a-descriptions-item>
                    <a-descriptions-item label="Serial Number">
                        {{ finance.serial_number }}
                    </a-descriptions-item>
                </a-descriptions>
            </a-col>
            <a-col :span="8">
                <a-descriptions title="Summary" bordered>
                    <a-descriptions-item :span="24" label="Invoice #">
                        {{ finance.order_id }}
                    </a-descriptions-item>
                    <a-descriptions-item :span="24" label="Date">
                        {{ finance.created_at }}
                    </a-descriptions-item>
                    <a-descriptions-item :span="24" label="Amount Due">
                        ${{ finance.payable }}
                    </a-descriptions-item>
                </a-descriptions>
            </a-col>
        </a-row>
    </div>
</template>
<script>
export default {
    data() {
        return {};
    },
    props: {
        finance: {
            default: () => {}
        }
    }
};
</script>
