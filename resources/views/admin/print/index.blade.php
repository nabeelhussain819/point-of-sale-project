<div class="print-view">
    <div class="ticket ">
        <!-- <img src="./logo.png" alt="Logo" /> -->
        <p class="centered">
            RECEIPT EXAMPLE <br />Address line 1 <br />Address line 2
        </p>
        <table>
            <thead>
            <tr>
                <th class="quantity">Q.</th>
                <th class="description">Description</th>
                <th class="price">$$</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="quantity">1.00</td>
                <td class="description">ARDUINO UNO R3</td>
                <td class="price">$25.00</td>
            </tr>
            <tr>
                <td class="quantity">2.00</td>
                <td class="description">JAVASCRIPT BOOK</td>
                <td class="price">$10.00</td>
            </tr>
            <tr>
                <td class="quantity">1.00</td>
                <td class="description">STICKER PACK</td>
                <td class="price">$10.00</td>
            </tr>
            <tr>
                <td class="quantity"></td>
                <td class="description">TOTAL</td>
                <td class="price">$55.00</td>
            </tr>
            </tbody>
        </table>
        <p class="centered">
            Thanks for your purchase! <br />parzibyte.me/blog
        </p>
    </div>
</div>

<style>
    * {
        font-size: 14px !important;
        font-family: "Times New Roman" !important;
        color: black;
    }

    td,
    th,
    tr,
    table {
        border-top: 1px solid black !important ;
        border-collapse: collapse !important;
    }

    td.description,
    th.description {
        width: 100px !important;
        max-width: 100px !important;
    }

    td.quantity,
    th.quantity {
        width: 40px !important;
        max-width: 40px !important;
        word-break: break-all !important;
    }

    td.price,
    th.price {
        width: 40px !important;
        max-width: 40px !important;
        word-break: break-all !important;
    }

    .centered {
        text-align: center !important;
        align-content: center !important;
    }

    .ticket {
        width: 155px !important;
        max-width: 155px !important;
    }

    img {
        max-width: inherit !important;
        width: inherit !important;
    }

    @media print {
        .hidden-print,
        .hidden-print * {
            display: none !important;
        }
        body * {
            all: revert;
        }
        body .print-view {
            display: block !important;
        }


        @page {
            margin: 0;
        }
        body {
            margin: 0;
        }
    }
</style>