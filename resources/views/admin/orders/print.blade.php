<div class="print-view">
    <div class="ticket ">
        <!-- <img src="./logo.png" alt="Logo" /> -->
        <h1> Invoice #{{ $order->id }}</h1>
        <p>
            {{ $store->name }} <br/>{{ $store->location }},{{
                            $store->city
                        }},{{ $store->state }}<br/>

            Phone :{{ $store->primary_phone }}<br/>
            Date : {{ $order->created_at->format("Y/m/d h:m A") }}<br/>
            <span class="customer-detail">
                        Customer :
                        <span>
                            @if(!empty($order->customer))
                                <span>{{
                                $order->customer->name_phone
                            }}</span>
                            @else
                                <span v-else>WalkIn Customer</span>
                            @endif
                        </span>
                    </span>
        </p>

        <table>
            <thead>
            <tr>
                <th class="quantity">Qty</th>
                <th class="description">Item</th>
                <th class="price">$$</th>
                <th class="price">total</th>
            </tr>
            </thead>
            <tbody>
            @foreach($order->products as $product)
                <tr class="@if(!empty($product->refund_id))strikeout    @endif">

                    <td class="quantity">{{$product->quantity}}</td>
                    <td class="description">{{$product->product->name}}
                        <br/>
                        <strong>{{$product->serial_number}}</strong>
                        <strong>@if(!empty($product->refund_id))*Refunded    @endif</strong>
                    </td>
                    <td class="price">${{$product->min_price}}</td>
                    <td class="price">${{$product->total}}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="3" class="sumary-heading">Tax %</td>

                <td class="price">{{empty($order->tax) ? 0 :$order->tax}}%</td>

            </tr>
            <tr>
                <td colspan="3" class="sumary-heading">Tax $</td>


                <td class="price">{{$order->sub_total - $order->without_tax}}$</td>
            </tr>

            {{--            <tr>--}}
            {{--                <td colspan="3" class="sumary-heading">Total Discount</td>--}}

            {{--                <td class="price">${{$order->discount}}</td>--}}
            {{--            </tr>--}}
            <tr>
                <td colspan="3" class="sumary-heading"><strong>Total</strong></td>

                <td class="price">${{$order->sub_total}}</td>
            </tr>

            </tbody>
        </table>
        <p class="centered">
            <h5>STORE POLICY</h5>
        <ol style="padding: 0;  ">
            <li>
                Products Phone, IPads, tablets, modems, watches,
                bluetooth devices and other gadgets These products (if
                unopened, no account created, and no sim inserted) are
                refundable within seven days, however, a restocking fee
                of 25% (or a fee of $150, whichever is greater) will
                apply. Items need to be exchanged or returned in the
                original condition. Items that are damaged, unsanitary,
                dented, scratched or missing major contents may be
                denied an exchange or return.
            </li>
            <li>
                Accessories Non refundable, can be exchanged within
                seven days. Must be in original condition and requires
                the original packaging. Further policies are posted on
                the counter For any questions or concerns, contact the
                general manager <br/>
                <strong
                >(see below for details) 703- 743-4384
                    aminali727@yahoo.com</strong
                >
            </li>
        </ol>
        <strong>Thank you for your business!</strong>
        {{--            Payment is--}}
        {{--            expected within 15 days; please process this invoice--}}
        {{--            within that time. There will be a 5% interest charge per--}}
        {{--            month on late invoices.--}}
        </p>
        {{--        <div class="design_by">Software Design by www.Afnato.com</div>--}}
    </div>
</div>
<button id="btnPrint" class="hidden-print">Print</button>
<button class="hidden-print" onclick="goBack()">Go Back</button>

<script>
    const $btnPrint = document.querySelector("#btnPrint");
    $btnPrint.addEventListener("click", () => {
        window.print();
    });

    function goBack() {
        window.history.back();
    }
</script>
<style>
    table {
        width: 100%;
    }

    #btnPrint {
        margin-top: 20px;
    }

    * {
        font-size: 14px !important;
        font-family: "Times New Roman" !important;
        color: black;
        -webkit-font-smoothing: antialiased;
    }

    td,
    th,
    tr,
    table {
        border-top: 1px solid black !important;
        border-collapse: collapse !important;
        text-align: center;
        text-transform: capitalize;
    }

    td.description,
    th.description {
        /*width: 100px !important;*/
        /*max-width: 100px !important;*/
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
        width: 100% !important;
        max-width: 280px !important;
        padding: 0 15px;
    }

    .sumary-heading {
        font-weight: bold;
        text-align: left;
    }

    img {
        max-width: inherit !important;
        width: inherit !important;
    }

    .design_by {
        background-color: black;
        color: white;
        font-size: 1.5em;
        text-align: center;
        -webkit-print-color-adjust: exact;
    }

    tr.strikeout td {
        text-decoration: line-through;
    }

    @media print {
        .hidden-print,
        .hidden-print * {
            display: none !important;
        }


        body .print-view {
            display: block !important;
        }

        @page {
            margin: 0 15px;
        }

        body {
            margin: 0;
        }
    }
</style>