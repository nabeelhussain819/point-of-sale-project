@extends('adminlte::page')

@section('title','Received Purchase Order')

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
                    @livewire('purchase-order.received',["purchaseOrder"=>$purchaseOrder])
                </div>
            </div>
        </div>
    </div>
@endsection
