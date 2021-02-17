@extends('adminlte::page')

@section('title','Edit Inventory')

@section('content')
    <div class="card">
        <div class="card-header">
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <div class="card-title"><h1>Edit Inventory</h1></div>
        </div>
        <div class="card-body">
            <form action="{{route('inventory.update',$inventory->id)}}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="exampleInputEmail1">Inventory Name</label>
                    <input type="text" name="name"  value="{{$inventory->name}}" class="form-control" placeholder="Name"/>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Quantity</label>
                    <input type="text" name="quantity" step="0" min="0"  value="{{$inventory->quantity}}" class="form-control" placeholder="Quantity"/>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Vendor</label>
                    <select name="vendor_id" class="form-control">
                        @foreach(\App\Models\Vendor::all() as $item)
                            <option value="{{$item->id}}" {{$item->id == $inventory->vendor_id ? 'selected' : ''}}>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Product</label>
                    <select name="product_id" class="form-control">
                        @foreach(\App\Models\Product::all() as $item)
                            <option value="{{$item->id}}" {{$item->id == $inventory->product_id ? 'selected' : ''}}>{{$item->name}}</option>
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
