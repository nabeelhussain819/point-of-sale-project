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
                @livewire('purchase-order', ['vendors'=>$vendors])

            </div>
        </div>

        <div class="card shadow rounded">
            <div class="card-body">

                @foreach($purchaseOrder as $po)
                    <div class="row alert alert-success">

                        <div class="col-sm-3">#{{$po->id}} {{$po->vendor->name}}</div>
                        <div class="col-sm-3">Total {{$po->price}}$</div>
                        <div class="col-sm-6">
                            <div style="display: flex;">

                                <a href="{{route('purchaseOrder.received',$po->id)}}" class="btn btn-info mr-1">
                                    <i class="fa fa-pen"></i>
                                </a>
                                <form action="{{route('purchase-vendor.delete',$po->id)}}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger ml-1" type="submit"><i
                                                class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>


                    </div>

                    <div>

                        {{------------------table---------------}}
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Products</th>
                                    <th scope="col">Product Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($po->products as $product)
                                    <tr>
                                        <td>{{$product->id}}</td>
                                        <td>{{$product->id}}</td>
                                        <td>{{$product->product->name}}</td>
                                        <td>{{$product->quantity}}</td>
                                        <td>{{$product->total}}</td>
                                    </tr>
                                @endforeach
                                </tbody>

                                <tfoot>
                                </tfoot>
                            </table>
                        </div>
                        {{------------------table---------------}}

                    </div>
                @endforeach



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
