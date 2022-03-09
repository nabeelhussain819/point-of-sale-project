<template>
    <span class="float-left w-100">
        <table class="table table-bordered">
            <tr>
                <td>Cash</td>
                <!-- // this is  jugard should be from DB  -->
                <td>${{ fixedValue(data) }}</td>
            </tr>
            <!-- <tr>
                <td>Cash back</td>
                <td>${{ data.cash_back }}</td>
            </tr> -->
            <tr>
                <td>Card</td>
                <td>${{ humanValue(data.card_paid) }}</td>
            </tr>
            <tr>
                <td>Total</td>
                <td>${{ humanValue(data.total) }}</td>
            </tr>
            <tr>
                <td>Tax</td>
                <td>${{ humanValue(data.tax) }}</td>
            </tr>
        </table>
    </span>
</template>
<script>
export default {
    props: {
        data: { type: Object },
    },
    data() {
        return {};
    },
    methods: {
        fixedValue(data) {
            let value = data.total - data.card_paid;
            return Number(value).toFixed(2);
        },
        humanValue(value) {
            return Number(value).toFixed(2);
        },
    },
};
</script>
