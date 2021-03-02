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
                    <div class="panel-heading"><b>Stock Transfer</b>
                        {{--                        <a class="btn btn-success float-right rounded shadow-lg" href="{{route('transfer.index')}}">See--}}
                        {{--                            Transfers</a>--}}
                    </div>
                    <form action="{{route('transfer.markasreceived',$transfer)}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">Request #</label>
                            <input type="text" class="form-control" name="request"
                                   placeholder="Request #" value="{{$transfer->request}}"/>
                        </div>
                        <div class="form-group">
                            <label for="">Date</label>
                            <input type="date" class="form-control" value="{{$transfer->date}}" name="date"
                            />
                        </div>
                        <div class="form-group">
                            <label>Store In</label>
                            <select name="store_in" id="" class="form-control">
                                <option value="">Please Select Store In</option>
                                @foreach(\App\Models\Store::all() as $item)
                                    <option value="{{$item->id}}" {{$transfer->store_in == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Store Out</label>
                            <select name="store_out" id="" class="form-control">
                                <option value="">Please Select Store Out</option>
                                @foreach(\App\Models\Store::all() as $item)
                                    <option value="{{$item->id}}" {{$transfer->store_out == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Reference</label>
                            <input type="text" class="form-control" name="reference"
                                   placeholder="Reference" value="{{$transfer->reference}}" required/>
                        </div>
                        <input type="hidden" name="type_id" value="1"/>
                        <table class="table" id="products_table">
                            <thead>
                            <tr>
                                <th scope="col">Quantity</th>
                                <th scope="col-xs-4">Products</th>
                            </tr>
                            </thead>
                            <tbody>
                            <input type="hidden" name="name" value="{{$transfer->inventory->name}}"/>
                            <input type="hidden" name="lookup" value="{{$transfer->inventory->lookup}}"/>
                            <input type="hidden" name="bin" value="{{$transfer->inventory->bin}}"/>
                            <input type="hidden" name="cost"
                                   value="{{$transfer->inventory->product->cost * $transfer->inventory->quantity}}"/>
                            <input type="hidden" name="extended_cost" value="{{$transfer->inventory->extended_cost}}"/>
                            <input type="hidden" name="description"
                                   value="Transfered From {{$transfer->storeOut->name}}"/>
                            <input type="hidden" name="vendor_id" value="{{$transfer->inventory->vendor_id}}"/>

                            <tr id="product0">
                                <td>
                                    <input type="text" class="form-control" name="quantity"
                                           placeholder="Enter Quantity" value="{{$transfer->quantity}}"/>
                                </td>
                                <td>
                                    <div class="input-group spinner">
                                        <div class="row">
                                            <select class="form-control" name="products[]">
                                                <option value="">Please Select Product</option>
                                                <option value="{{$transfer->inventory->product->id}}"
                                                        selected>{{$transfer->inventory->product->name}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr id="product1">
                            </tr>
                        </table>
                        <div class="row">
                            <div class="col-md-12">
                                <button id="add_row" class="btn btn-primary float-left shadow-lg">+ Add Row</button>
                                <button id='delete_row' class="float-right btn btn-danger shadow-lg">- Delete Row
                                </button>
                            </div>
                        </div>
                        <br>
                        <div>
                            <button class="btn btn-success font-weight-bold shadow rounded float-right" type="submit">
                                Mark As Received
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            let row_number = 1;
            $("#add_row").click(function (e) {
                e.preventDefault();
                let new_row_number = row_number - 1;
                $('#product' + row_number).html($('#product' + new_row_number).html()).find('td:first-child');
                $('#products_table').append('<tr id="product' + (row_number + 1) + '"></tr>');
                row_number++;
            });

            $("#delete_row").click(function (e) {
                e.preventDefault();
                if (row_number > 1) {
                    $("#product" + (row_number - 1)).html('');
                    row_number--;
                }
            });
        });

        function calculateColumn(index) {
            // var total = 0;
            // $('table tr').each(function () {
            //     var value = parseInt($('td ', this).eq(index).text());
            //     if (!isNaN(value)) {
            //         total += value;
            //     }
            // });
            // $('table tfoot td').eq(index).text('Total Sum: ' + total);
        }
    </script>
@endsection
