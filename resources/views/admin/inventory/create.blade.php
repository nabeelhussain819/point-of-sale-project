@extends('adminlte::page')

@section('title','Create Inventory')

@section('content')
    <div class="container">
        <h4>New Inventory</h4>
        <div class="card shadow rounded">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <form action="{{route('inventory.store')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Inventory Name</label>
                                <input type="text" name="name" class="form-control" placeholder="Name"/>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Quantity</label>
                                <input type="text" name="quantity" step="0" min="0" class="form-control"
                                       placeholder="Quantity"/>
                            </div>
                    </div>
                    <div class="col-lg-6">      <div class="form-group">
                            <label for="exampleInputPassword1">Vendor</label>
                            <select name="vendor_id" class="form-control">
                                @foreach(\App\Models\Vendor::all() as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Product</label>
                            <select name="product_id" class="form-control">
                                @foreach(\App\Models\Product::all() as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success float-right font-weight-bold">Add New</button>
                        </form></div>

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
    </div>

@endsection
