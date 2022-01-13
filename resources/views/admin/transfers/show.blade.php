@extends('adminlte::page')

@section('title','Received Transfer')

@section('content')

    <div class="container">
        <div class="card">
            <h5 class="card-header"><a href="#" class="badge badge-primary"> {{$transfer->storeOut->name}}</a> to <a
                        href="#" class="badge badge-success"> {{$transfer->storeIn->name}}</a></h5>
            <div class="card-body">

                <div class="row">

                    <div class="card border-primary col-4 mb-3">
                        <div class="card-header">Transfer Date</div>
                        <div class="card-body text-primary">
                            <h5 class="card-title">{{$transfer->transfer_date}}</h5>

                        </div>
                    </div>
                    <div class="col-2"></div>
                    <div class="card text-white border-secondary bg-success  col-4 mb-3">
                        <div class="card-header">Received Date</div>
                        <div class="card-body text-white ">
                            <h5 class="card-title text-white">{{$transfer->received_date}}</h5>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
@endsection
