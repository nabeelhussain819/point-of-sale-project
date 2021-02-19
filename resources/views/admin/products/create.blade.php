@extends('adminlte::page')

@section('title','Create A New Product')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <h1>Create A New Product</h1>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf
                        <h4>Product Information</h4>
                        <div class="form-group">
                            <label for="">Product Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Name"
                                   required/>
                        </div>
                        <div class="form-group">
                            <label for="">Product Full Name</label>
                            <input type="text" class="form-control" name="description" placeholder="Enter description"
                                   required/>
                        </div>
                        <div class="form-group">
                            <label for="">UPC</label>
                            <input type="text" class="form-control" name="UPC" id="" aria-describedby="emailHelp"
                                   placeholder="Enter UPC" required/>
                        </div>
                        <div class="form-group">
                            <label for="">Department</label>
                            <select class="form-control" name="department_id">
                                @foreach(\App\Models\Department::all() as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Category</label>
                            <select class="form-control" name="category_id">
                                @foreach(\App\Models\Category::all() as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <h4>Product Cost</h4>
                    <div class="form-group">
                        <label for="">Cost</label>
                        <input type="number" class="form-control" name="cost" id="" placeholder="Enter Cost" required/>
                    </div>
                    <div class="form-group">
                        <label for="">Retail Price</label>
                        <input type="number" class="form-control" name="retail_price" placeholder="Enter Retail Price"
                               required/>
                    </div>

                    <div class="form-group">
                        <label for="">Minimum Price</label>
                        <input type="text" class="form-control" name="min_price" placeholder="Enter Minimum Price"/>
                    </div>
                    <div class="form-group">
                        <label>Taxable</label>
                        <select name="taxable" class="form-control">
                            <option value="0">Not Taxable</option>
                            <option value="1">Taxable</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Active</label>
                        <select name="active" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Not Active</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary float-right">Submit</button>

                    </form>
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