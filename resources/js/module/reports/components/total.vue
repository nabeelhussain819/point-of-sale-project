<template>
    <span class="float-left  w-100">
        <table class="table table-bordered ">
            <tr>
                <td>Cash</td>
                <!-- // this is  jugard should be from DB  -->
                <td>${{  data.total - data.card_paid}}</td>
            </tr>
             <!-- <tr>
                <td>Cash back</td>
                <td>${{ data.cash_back }}</td>
            </tr> -->
            <tr>
                <td>Card</td>
                <td>${{ data.card_paid }}</td>
            </tr>
            <tr>
                <td>Total</td>
                <td>${{ data.total }}</td>
            </tr>
             <tr>
                <td>Tax</td>
                <td>${{ data.tax }}</td>
            </tr>
        </table>
    </span>
</template>
<script>
export default {
    props: {
        data: { type: Object }
    },
    data() {
        return {};
    }
};
</script>
