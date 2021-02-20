@extends('adminlte::page')

@section('title','All Sales')

@section('content')

    <div class="container">
        <div class="panel panel-default">
            <div class="panel-heading"><b>New Order</b>
            </div>
            <form action="POST" method="{{route('sales.store')}}">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th class="col-xs-3">Customer</th>
                    <th class="col-xs-3">Description</th>
                    <th class="col-xs-3">Quantity</th>
                    <th class="col-xs-3">Product</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <div class="radio">
                            <select class="form-control" name="customer_id">
                                @foreach($customers as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="description"
                               placeholder="Enter description"
                               required/>
                    </td>
                    <td>
                        <input type="number" class="form-control" name="quantity" id=""
                               placeholder="Enter Quantity" required/>
                    </td>
                    <td>
                        <div class="input-group spinner">
                            <div class="row">
                                @foreach(\App\Models\Product::all() as $item)
                                    <div class="col">
                                        <p>{{$item->name}}</p>
                                        <input type="checkbox" name="products[]" value="{{$item->id}}"/>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary float-right" >Add</button>
                    </td>
                </tr>
                </tbody>
                <tfoot>
                <td>

                </td>
                </tfoot>
            </table>
            </form>
        </div>
        <div class="table-responsive">

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Order Id</th>
                    <th scope="col">Customer Name</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Description</th>
                    <th scope="col" class="unitprice">Unit Price</th>
                    {{--                    <th scope="col">Action</th>--}}
                </tr>
                </thead>
                <tbody>
                @php
                    $count = 1;
                @endphp
                @forelse(\App\Models\OrderProduct::with('customer','product')->get() as $item)
                    <tr>
                        <td>{{$count++}}</td>
                        <td>{{$item->order_id}}</td>
                        <td>{{$item->customer->name}}</td>
                        <td>{{$item->product->name}}</td>
                        <td>{{$item->product->description}}</td>
                        <td>{{$item->product->cost}}</td>
                        <td>
                            <div style="display: flex;margin:4px">
                                {{--                                <a class="btn btn-info" href="{{route('sales.edit',$item->id)}}"><i--}}
                                {{--                                            class="fa fa-pen"></i></a>--}}
                                {{--                                <form action="{{route('sales.destroy',$item->id)}}" method="POST">--}}
                                {{--                                    @method('DELETE')--}}
                                {{--                                    @csrf--}}
                                {{--                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>--}}
                                {{--                                </form>--}}
                            </div>
                        </td>
                    </tr>
                @empty
                    {{--                    <a href="{{route('sales.create')}}">No Sales yet</a>--}}
                @endforelse
                </tbody>
                {{--                <td>Total Sum Of Quantity: {{$item->inventory->quantity * $item->inventory->product->cost}}</td>--}}
                <tfoot>
                {{--                <tr>--}}
                {{--                    <td style="display: none;">Total:</td>--}}
                {{--                    <td style="display: none;">Total Quantity</td>--}}
                {{--                    <td style="display: none;">Total Quantity</td>--}}
                {{--                    <td style="display: none;">Total Quantity</td>--}}
                {{--                    <td></td>--}}
                {{--                </tr>--}}
                </tfoot>
            </table>
        </div>

    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            // $("table thead th").each(function (i) {
            //     calculateColumn(i);
            // });
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
