@extends('adminlte::page')

@section('title','Create A New Sale')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h1>Create A New Sale</h1>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-6 col-sm-6">
                        <form action="{{ route('sales.store') }}" method="POST">
                            @csrf
                            <h4>Sales Information</h4>
                            <div class="form-group">
                                <label for="">Customer</label>
                                <select class="form-control" name="customer_id">
                                @foreach($customers as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <input type="text" class="form-control" name="description"
                                       placeholder="Enter description"
                                       required/>
                            </div>
                            <div class="form-group">
                                <label for="">Tax</label>
                                <input type="number" class="form-control" name="tax" id=""
                                       placeholder="Enter Unit Price" required/>
                            </div>
                            <div class="form-group">
                                <label for="">Inventory</label>
                                <select class="form-control" name="inventory_id">
                                    @foreach($inventory as $item)
                                        <option value="{{$item->id}}">{{$item->product->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Products</label>
                                <div class="row">
                                    @foreach(\App\Models\Product::all() as $item)
                                        <div class="col-2">
                                            <p>{{$item->name}}</p>
                                            <input type="checkbox" name="products[]" value="{{$item->id}}"/>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary float-right">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @if (isset($errors) && count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection