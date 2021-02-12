@extends('adminlte::page')

@section('title','Create Inventory')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title"><h1>Create Inventory</h1></div>
        </div>
        <div class="card-body">
            <form action="{{route('inventory.store')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1">Inventory Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Name"/>
                </div>
                <div class="form-group">
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
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
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
