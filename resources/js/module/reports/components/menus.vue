<template>
    <div>
        <a-menu v-model="current" mode="horizontal">
            <a-menu-item key="mail"
                ><a href="/reports/sales">Sales</a>
            </a-menu-item>
            <a-menu-item key="po"
                ><a href="/reports/purchase">Purchase Order</a>
            </a-menu-item>
            <a-menu-item key="t"
                ><a href="/reports/transfer">Transfer</a>
            </a-menu-item>
            <a-menu-item key="r"
                ><a href="/reports/repair">Repair</a>
            </a-menu-item>
            <a-menu-item key="return"
                ><a href="/reports/return">Return</a>
            </a-menu-item>
            <a-menu-item key="Finance"
                ><a href="/reports/finance">Finance</a>
            </a-menu-item>
        </a-menu>
    </div>
</template>
<script>
export default {
    data() {
        return {
            current: ["mail"]
        };
    }
};
</script>
