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
                    <div class="row alert alert-success mt-1 mb-1">

                        <div class="col-sm-4 mt-1">#{{$po->id}} {{$po->vendor->name}}</div>
                        <div class="col-sm-4 mt-1">Total {{$po->price}}$</div>
                        <div class="col-sm-4">
                            <div style="display: flex;">

                                <a href="{{route('purchaseOrder.received',$po->id)}}" class="btn btn-info mr-1">
                                    Receive
                                </a>
                                <form action="{{route('purchase-order.destroy',$po->id)}}" method="POST">
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
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($po->products as $product)
                                    <tr>
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

@endsection

@section('js')

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="text/javascript">
        let isFirst = false;
        $(document).ready(function () {
            document.addEventListener("livewire:load", () => {
                Livewire.hook('message.processed', (message, component) => {
                    $('.product_select').select2();

                }); });
            $(document).on('click', "#addLivewire", function () {
                if (!isFirst) {
                    console.log("asd");
                }
                isFirst = true;
            })

        });
    </script>
@endsection