@extends('adminlte::page')

@section('title','Purchase Order')

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
                    {{--                    <div class="panel-heading"><b>New Order</b>--}}
                </div>
                <form action="{{route('sales.store')}}" method="POST">
                    <div class="form-group">
                        <label>Vendor </label>
                        <select class="form-control" name="vendor_id">
                            <option value="">Please Select Vendor</option>
                            @foreach($vendors as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description </label>
                        <input type="text" class="form-control" name="description"
                               placeholder="Enter description"/>
                    </div>

                    <br>
                    <input type="hidden" name="type_id" value="2"/>
                    @csrf
                    <table class="table" id="products_table">
                        <thead>
                        <tr>
                            <th class="col-xs-4">Lookup</th>
                            <th class="col-xs-4">Product</th>
                            <th class="col-xs-4">Quantity</th>
                            <th scope="col-xs-4">Store</th>
                            <th scope="col-xs-4">Stock</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr id="product0">
                            <td>
                                <input type="text" class="form-control" name="lookup"
                                       placeholder="Enter Lookup"/>
                            </td>
                            <td>
                                <div class="input-group spinner">
                                    <div class="row">

                                        <select class="form-control" name="products[]">
                                            <option value="">Please Select Product</option>
                                            @foreach(\App\Models\Product::all() as $item)
                                                <option value="{{$item->id}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </td>
                            <td><input type="text" class="form-control" name="quantity"
                                       placeholder="Enter Quantity"/>

                            </td>
                            <td>
                                <select class="form-control" name="store_id">
                                    <option value="">Please Select Store</option>

                                    @foreach(\App\Models\Store::all() as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-control" name="stock_id">
                                    <option value="">Please Select Stock</option>

                                    @foreach(\App\Models\StockBin::all() as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </td>


                        </tr>
                        <tr id="product1">
                        </tr>
                    </table>
                    <div class="row">
                        <div class="col-md-12">
                            <button id="add_row" class="btn btn-primary float-left shadow-lg">+ Add Row</button>
                            <button id='delete_row' class="float-right btn btn-danger shadow-lg">- Delete Row</button>
                        </div>
                    </div>
                    <br>
                    <div>
                        <button class="btn btn-success font-weight-bold shadow rounded float-right" type="submit">Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

    <div class="card shadow rounded">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Vendor Name</th>
                        <th scope="col">Mailing Address</th>
                        <th scope="col">Contact</th>
                        <th scope="col">Products</th>
                        <th scope="col">Product Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Total Price</th>
                        <th scope="col">Actions</th>
                        {{--                    <th scope="col">Action</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @forelse($vendors as $item)
                        <tr>
                            <td>{{$item->id}}</td>
                            <td>{{$item->name}}</td>
                            <td>{{$item->mailing_address}}</td>
                            <td>{{$item->telephone}}</td>

                            <td>
                                <ul>
                                    @foreach($item->orderProducts as $products)
                                        <li> {{$products->inventory->product->name}}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>
                                <ul>
                                    @foreach($item->orderProducts as $products)
                                        <li> {{$products->inventory->product->cost}}</li>
                                    @endforeach
                                </ul>
                            </td>

                            <td>
                                @foreach($item->orderProducts as $products)
                                    <li> {{$products->quantity}}</li>
                                @endforeach
                            </td>
                            <td>
                                @foreach($item->orderProducts as $products)
                                    <li> {{$products->quantity * $products->inventory->product->cost}}</li>
                                @endforeach
                            </td>
                            <td>
                                <div style="display: flex;">
                                    <a href="{{route('purchase.received',$item)}}" class="btn btn-info mr-1">
                                        <i class="fa fa-pen"></i>
                                    </a>
                                    <form action="{{route('purchase-vendor.delete',$item->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger ml-1" type="submit"><i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                    {{--                <td>Total Sum Of Quantity: {{$item->inventory->quantity * $item->inventory->product->cost}}</td>--}}
                    <tfoot>
                    </tfoot>
                </table>
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
