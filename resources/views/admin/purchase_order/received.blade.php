@extends('adminlte::page')

@section('title','Received Transfer')

@section('content')

    <div class="container">
        @if (isset($errors) && count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="card shadow rounded">
            <div class="card-body">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Purchase Order Received</b>
                    </div>
                    <form action="{{route('purchaseOrder.received-done',$purchaseOrder)}}" method="POST">
                        @csrf
                        <table class="table" id="products_table">
                            <thead>
                            <tr>
                                <th class="col-xs-4">Product</th>
                                <th class="col-xs-4">Product Cost</th>
                                <th class="col-xs-4">Quantity</th>
                                <th class="col-xs-4">Price</th>
                                <th class="col-xs-4">Total Price</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php 
                                $sReceivedDate = $bHashSerialNumber ='' ;
                                $iQuantity = $iProductId = '0' ;
                            @endphp
                            @foreach($purchaseOrder->products as $key => $value)
                                @php 
                                    $bHashSerialNumber =  $value->product->has_serial_number; 
                                    $iQuantity = $value->quantity;
                                    $iProductId = $value->product->id;
                                    $sReceivedDate = $purchaseOrder->received_date;
                                @endphp
                                <tr id="product0">
                                    <td>
                                        <div class="input-group spinner">
                                            <div class="row">
                                               <input type="text" disabled value="{{$value->product->name}}" class="form-control"/>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group spinner">
                                            <div class="row">
                                                <input type="text" disabled value="{{$value->product->cost}}" class="form-control"/>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number"
                                               class="form-control"
                                               value="{{$value->quantity}}"
                                               placeholder="Enter Quantity"/>
                                    </td>

                                    <td>
                                        <input type="number" class="form-control"
                                              value="{{$purchaseOrder->price}}" />
                                    </td>
                                    <td>
                                        <input type="number" class="form-control"
                                               value="{{$purchaseOrder->expected_price}}" />
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <div>
                            <button type="submit" class="btn btn-block btn-success"> Mark As Received </button>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
