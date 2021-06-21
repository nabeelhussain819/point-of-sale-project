<template>
    <div class="only-for-print">
        <div id="invoice-POS">
            <center id="top">
                <div class="logo"></div>
                <div class="info">
                    <h2>{{ printDetail.store.name }}</h2>
                    Invoice #{{ order.id }}
                </div>

                <!--End Info-->
            </center>
            <!--End InvoiceTop-->

            <div id="mid">
                <div class="info">
                    <h2>Contact Info</h2>
                    <p>
                        Address :{{ printDetail.store.location }},{{
                            printDetail.store.city
                        }},{{ printDetail.store.state }}<br />
                        Email : Requried Email<br />
                        Phone :{{ printDetail.store.primary_phone }}<br />
                        Date : {{ currentDateTime }}<br />
                        Customer :
                        <span v-if="!isEmpty(customer)">{{
                            customer.name_phone
                        }}</span>
                        <span v-else>WalkIn Customer</span>
                    </p>
                </div>
            </div>
            <!--End Invoice Mid-->

            <div id="bot">
                <div id="table">
                    <table>
                        <tr class="tabletitle">
                            <td class="item"><h2>Item</h2></td>
                            <td class="Hours"><h2>Qty</h2></td>
                            <td class="Rate"><h2>Unit price</h2></td>
                            <td class="Rate"><h2>Sub Total</h2></td>
                        </tr>

                        <tr
                            v-for="product in products"
                            :key="product.id"
                            class="service"
                        >
                            <td class="tableitem">
                                <p class="itemtext">{{ product.name }}</p>
                                <p
                                    v-if="!isEmpty(product.serial_number)"
                                    class="itemtext"
                                >
                                    S.no:<strong>{{
                                        product.serial_number
                                    }}</strong>
                                </p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext">{{ product.quantity }}</p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext">${{ product.min_price }}</p>
                            </td>
                            <td class="tableitem">
                                <p class="itemtext">${{ product.total }}</p>
                            </td>
                        </tr>

                        <tr class="tabletitle">
                            <td></td>
                            <td class="Rate"><h2>Tax</h2></td>
                            <td colspan="2" class="payment">
                                <h2>{{ billSummary.tax }} %</h2>
                            </td>
                        </tr>

                        <tr class="tabletitle">
                            <td></td>
                            <td class="Rate"><h2>Discount</h2></td>
                            <td colspan="2" class="payment">
                                <h2>$ {{ billSummary.discount }}</h2>
                            </td>
                        </tr>
                        <tr class="tabletitle">
                            <td></td>
                            <td class="Rate"><h2>Total</h2></td>
                            <td colspan="2" class="payment">
                                <h2>${{ billSummary.subTotal }}</h2>
                            </td>
                        </tr>
                    </table>
                </div>
                <!--End Table-->

                <div id="legalcopy">
                    <p class="legal">
                        <strong>Thank you for your business!</strong> Payment is
                        expected within 31 days; please process this invoice
                        within that time. There will be a 5% interest charge per
                        month on late invoices.
                    </p>
                </div>
                <div class="design_by">Software Design by www.Afnato.com</div>
            </div>
            <!--End InvoiceBot-->
        </div>
        <!--End Invoice-->
    </div>
</template>
<script>
import { isEmpty } from "../../services/helpers";
import moment from "moment";
export default {
    data() {
        return {
            isEmpty,
            currentDateTime: moment().format("MMMM Do YYYY, h:mm:ss a")
        };
    },
    props: {
        products: { default: () => [] },
        billSummary: { default: () => {} },
        customer: { default: () => {} },
        order: { default: () => {} },
        printDetail: { default: () => [] }
    }
};
</script>

<style scoped lang="scss">
.only-for-print {
    display: none;
}
#invoice-POS * {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
#invoice-POS {
    box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
    // padding: 2mm;
    margin: 0 auto;
    width: 100%;
    background: #fff;
    border: 1px solid #eee;
    page-break-after: always;

    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
#invoice-POS ::selection {
    color: #fff;
}
#invoice-POS ::moz-selection {
    color: #fff;
}
#invoice-POS h1 {
    font-size: 10em;
    color: #222;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
#invoice-POS h2 {
    font-size: 2.5em;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
#invoice-POS h3 {
    font-size: 1.2em;
    font-weight: 300;
    line-height: 2em;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
#invoice-POS p {
    font-size: 1.5em;
    color: #666;
    line-height: 1.2em;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
#invoice-POS #top,
#invoice-POS #mid,
#invoice-POS #bot {
    /* Targets all id with 'col-' */
    border-bottom: 1px solid #eee;
}
#invoice-POS #top {
    min-height: 100px;
}
#invoice-POS #mid {
    min-height: 80px;
}
#invoice-POS #bot {
    min-height: 50px;
}
#invoice-POS #top .logo {
    height: 60px;
    width: 60px;
    background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
    background-size: 60px 60px;
}
#invoice-POS .clientlogo {
    float: left;
    height: 60px;
    width: 60px;
    background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
    background-size: 60px 60px;
    border-radius: 50px;
}
#invoice-POS .info {
    font-size: 20pt;
    display: block;
    margin-left: 0;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
#invoice-POS .title {
    float: right;
}
#invoice-POS .title p {
    text-align: right;
}
#invoice-POS table {
    width: 100%;
    border-collapse: collapse;
}
#invoice-POS .tabletitle {
    font-size: 1.5em;
    -webkit-print-color-adjust: exact;
    background: #eee;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
#invoice-POS .service {
    border-bottom: 1px solid #eee;
}
#invoice-POS .item {
    width: 24mm;
}
#invoice-POS .itemtext {
    font-size: 2em;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
#invoice-POS #legalcopy {
    margin-top: 5mm;
}
#invoice-POS .design_by {
    background-color: black;
    color: white;
    font-size: 1.5em;
    text-align: center;
    -webkit-print-color-adjust: exact;
}
@media print {
    .page-break {
        display: block;
        page-break-before: always;
    }
    .only-for-print {
        display: block;
    }
}
</style>
