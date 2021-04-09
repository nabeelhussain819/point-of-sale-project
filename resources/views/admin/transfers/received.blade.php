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
                    <div class="panel-heading"><b>Received Stock Transfer</b>
                        {{--                        <a class="btn btn-success float-right rounded shadow-lg" href="{{route('transfer.index')}}">See--}}
                        {{--                            Transfers</a>--}}
                    </div>
                    <form action="{{route('transfer.markasreceived',$transfer)}}" method="POST">
                        @csrf

                        @livewire('stock-transfer-product-field', ['stores'=>$stores,'transfer'=>$transfer])
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
@endsection
