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
                <form action="{{route('purchase-order.store')}}" method="POST">
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

                    <div class="form-group">
                        <label>Description </label>
                        <select class="form-control" name="products[{{$key}}][store_id]">
                            <option value="">Please Select Store</option>

                            @foreach(\App\Models\Store::all() as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <br>
                    <input type="hidden" name="type_id" value="2"/>
                    @csrf
                    <livewire:product-fields/>
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
                                            <li> {{$products->product->name}}</li>
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
                                            <button class="btn btn-danger ml-1" type="submit"><i
                                                        class="fa fa-trash"></i>
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
        // @todo remove this
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
