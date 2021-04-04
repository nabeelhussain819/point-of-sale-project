<div>

    <form action="{{route('purchaseOrder.received-done',$purchaseOrder->id)}}" method="POST">
        @csrf

        <table class="table">
            <tr>
                <td> Vendor: <b>{{$purchaseOrder->vendor->name}}</b></td>
                <td>Store:<b>{{$purchaseOrder->store->name}}</b></td>
                <td>Date:<b>{{$purchaseOrder->expected_date->diffForHumans()}}</b></td>
            </tr>
        </table>


        @livewire('product-fields',["purchaseOrder"=>$purchaseOrder])

        @if(empty($purchaseOrder->received_date))
            <button class="btn btn-success" type="submit">submit</button>

        @else
            <div class="alert alert-danger">
                Your order has been received
            </div>
        @endif
        <button class="btn btn-success" type="submit">submit</button>
    </form>

</div>
