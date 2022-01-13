@extends('adminlte::page')

@section('title','Received Transfer')

@section('content')

    <div class="container">
        <div class="card">
            <h5 class="card-header"><a href="#" class="badge badge-primary"> {{$transfer->storeOut->name}}</a> to <a
                        href="#" class="badge badge-success"> {{$transfer->storeIn->name}}</a></h5>
            <div class="card-body">

                <div class="row">


                    <div class="card border-primary  bg-info  col-12 col-md-4">
                        <div class="card-header">Transfer Date</div>
                        <div class="card-body text-primary">
                            <h5 class="card-title text-white">{{$transfer->transfer_date}}</h5>

                        </div>
                    </div>

                    <div class="card text-white border-secondary bg-warning   col-12 col-md-4">
                        <div class="card-header text-white">Received Date</div>
                        <div class="card-body text-white ">
                            <h5 class="card-title text-white">{{$transfer->received_date}}</h5>

                        </div>
                    </div>

                    <div class="card text-white border-secondary {{empty($transfer->received_date) ? 'bg-danger ':'bg-success'}}  col-12 col-md-4">
                        <div class="card-header">Is Received</div>
                        <div class="card-body text-white ">
                            <h5 class="card-title text-white">
                                @if(empty($transfer->received_date))
                                    No
                                @else
                                    Yes
                                @endif

                            </h5>

                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card">
            <h5 class="card-header">Products</h5>

            @foreach($transfer->products as $product)

                <div class="card bg-light mb-3">
                    <div class="card-header text-capitalize">{{$product->product->name}} <span class="float-right">Quantity : {{$product->quantity}}</span>
                    </div>
                    <div class="card-body">

                        @foreach($product->getSerialNumbers() as $serialNumbers)

                            <ul>
                                <li>{{$serialNumbers->serial_no}}</li>
                            </ul>
                        @endforeach
                    </div>
                </div>

            @endforeach
        </div>

    </div>
@endsection
