<template>
    <div>
        <a-menu v-model="activeKey" mode="horizontal">
            <a-menu-item key="sale"
                ><a href="/reports/sales">Sales</a>
            </a-menu-item>
            <a-menu-item key="po"
                ><a href="/reports/purchase">Purchase Order</a>
            </a-menu-item>
            <a-menu-item key="transfer"
                ><a href="/reports/transfer">Transfer</a>
            </a-menu-item>
            <a-menu-item key="repair"
                ><a href="/reports/repair">Repair</a>
            </a-menu-item>
            <a-menu-item key="refund"
                ><a href="/reports/return">Return</a>
            </a-menu-item>
            <a-menu-item key="finance"
                ><a href="/reports/finance">Finance</a>
            </a-menu-item>
        </a-menu>
    </div>
</template>
<script>
export default {
    props: { activeKey: { type: Array, default: ["sale"] } },
    data() {
        return {
            current: ["mail"]
        };
    }
};
</script>
