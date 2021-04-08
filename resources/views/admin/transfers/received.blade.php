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
                            <input type="text" disabled="true" class="form-control" name="request_id"
                                   placeholder="Request #" value="{{$transfer->request_id}}"/>
                        </div>
                        <div class="form-group">
                            <label for="">Date </label>
                            <input type="date" disabled="true" class="form-control"
                                   value="{{$transfer->transfer_date->format("Y-m-d")}}"
                                   name="date"
                            />
                        </div>
                        <div class="form-group">
                            <label>Store In</label>
                            <select name="store_in" disabled="true" class="form-control">
                                <option value="">Please Select Store In</option>
                                @foreach($stores as $item)
                                    <option value="{{$item->id}}" {{$transfer->store_in_id == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Store Out</label>
                            <select disabled="true" name="store_out_id" class="form-control">
                                <option value="">Please Select Store Out</option>
                                @foreach($stores as $item)
                                    <option value="{{$item->id}}" {{$transfer->store_out_id == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @livewire('stock-transfer-product-field', ['stores'=>$stores,'dbProducts'=>$transfer->products])
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
