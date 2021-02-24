@extends('adminlte::page')

@section('title','Edit Inventory')

@section('content')
    <div class="container">
        <h4>Edit Inventory</h4>
        <div class="card shadow rounded">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <form action="{{route('inventory.update',$inventory->id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="exampleInputEmail1">Inventory Name</label>
                                <input type="text" name="name" value="{{$inventory->name}}" class="form-control"
                                       placeholder="Name"/>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Quantity</label>
                                <input type="text" name="quantity" step="0" min="0" value="{{$inventory->quantity}}"
                                       class="form-control" placeholder="Quantity"/>
                            </div>
                            <div class="form-group">
                                <label>Lookup</label>
                                <input type="number" name="lookup" value="{{$inventory->lookup}}" class="form-control"
                                       placeholder="Lookup"/>
                            </div>
                            <div class="form-group">
                                <label>Cost</label>
                                <input type="number" name="cost" value="{{$inventory->cost}}" class="form-control"
                                       placeholder="Cost"/>
                            </div>
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="text" name="quantity" value="{{$inventory->quantity}}" step="0" min="0"
                                       class="form-control"
                                       placeholder="Quantity"/>
                            </div>


                            <div class="form-group">
                                <label>Bin</label>
                                <input type="text" name="bin" class="form-control" value="{{$inventory->bin}}"
                                       placeholder="Bin..."/>
                            </div>
                    </div>
                    <div class="col-lg-6">
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
                        <div class="form-group">
                            <label>UPC</label>
                            <input type="number" value="{{$inventory->UPC}}" name="UPC" class="form-control"
                                   placeholder="UPC"/>
                        </div>
                        <div class="form-group">
                            <label>Extended Cost</label>
                            <input type="number" name="extended_cost" value="{{$inventory->extended_cost}}"
                                   class="form-control"
                                   placeholder="Lookup"/>
                        </div>

                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control"
                            >{{$inventory->description}}</textarea>
                        </div>
                        <button type="submit" class="btn btn-success float-right font-weight-bold">Edit</button>
                        </form>
                    </div>

                </div>
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

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
