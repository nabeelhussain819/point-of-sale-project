@extends('adminlte::page')

@section('title','Edit Product')
@section('content')
    <div class="card">
        <div class="card-header">
            @if(session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <div class="card-title">
                <h1>Edit Product</h1>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <form action="{{ route('products.update',$product->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <h4>Product Information</h4>
                        <div class="form-group">
                            <label for="">Product Name</label>
                            <input type="text" class="form-control" name="name" value="{{$product->name}}" placeholder="Enter First Name"
                                   required/>
                        </div>
                        <div class="form-group">
                            <label for="">Product Full Name</label>
                            <input type="text" class="form-control" name="description" value="{{$product->description}}" placeholder="Enter Last Name"
                                   required/>
                        </div>
                        <div class="form-group">
                            <label for="">UPC</label>
                            <input type="text" class="form-control" name="UPC" id="" value="{{$product->UPC}}" aria-describedby="emailHelp"
                                   placeholder="Enter Full Name" required/>
                        </div>
                        <div class="form-group">
                            <label for="">Department</label>
                            <select class="form-control" name="department_id">
                                @foreach(\App\Models\Department::all() as $item)
                                    <option value="{{$item->id}}" {{$item->id == $product->department_id ? 'selected' : ''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Category</label>
                            <select class="form-control" name="category_id">
                                @foreach(\App\Models\Category::all() as $item)
                                    <option value="{{$item->id}}" {{$item->id == $product->category_id ? 'selected' : ''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>

                <div class="col-lg-6 col-md-6 col-sm-6">
                    <h4>Product Cost</h4>
                    <div class="form-group">
                        <label for="">Cost</label>
                        <input type="number" class="form-control" name="cost" value="{{$product->cost}}" id="" placeholder="Enter Email" required/>
                    </div>
                    <div class="form-group">
                        <label for="">Retail Price</label>
                        <input type="number" class="form-control" name="retail_price" value="{{$product->retail_price}}" placeholder="Enter Password"
                               required/>
                    </div>

                    <div class="form-group">
                        <label for="">Minimum Price</label>
                        <input type="text" class="form-control" name="min_price" value="{{$product->min_price}}" placeholder="Enter Driver License"/>
                    </div>
                    <div class="form-group">
                        <label>Active</label>
                        <select name="active" class="form-control">
                            <option value="1" {{$product->active == 1 ? 'selected' : ''}}>Active</option>
                            <option value="0" {{$product->active == 0 ? 'selected' : ''}}>Not Active</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="checkbox">Has Serial</label>
                        {{--@todo Armash please handle the checkbox--}}
                        <input type="hidden" name="has_serial_number" value="0">
                        <input type="checkbox" name="has_serial_number" {{$product->has_serial_number == 1 ? 'checked' : ''}}>

                        {{--<input id="checkbox" type="checkbox" name="has_serial_number" {{$product->has_serial_number == 1 ? 'checked' : ''}}>--}}
                    </div>
                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('script')
    <script>

    </script>
@endsection