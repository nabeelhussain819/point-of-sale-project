@extends('adminlte::page')

@section('title','Products')
@section('content')

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">New Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                                <h4>Product Information</h4>
                                <div class="form-group">
                                    <label for="">Product Name</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter Name"
                                           required value="{{old('name')}}"/>
                                </div>
                                <div class="form-group">
                                    <label for="">Product Full Name</label>
                                    <input type="text" class="form-control" name="description" placeholder="Enter description"
                                           required value="{{old('description')}}"/>
                                </div>
                                <div class="form-group">
                                    <label for="">UPC</label>
                                    <input type="text" class="form-control" name="UPC" id="" aria-describedby="emailHelp"
                                           placeholder="Enter UPC" required value="{{old('UPC')}}"/>
                                </div>
                                <div class="form-group">
                                    <label for="">Department</label>
                                    <select class="form-control" name="department_id">
                                        <option>Please Select Department</option>
                                    @foreach(\App\Models\Department::all() as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">Category</label>
                                    <select class="form-control" name="category_id">
                                        <option>Please Select Category</option>
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
                                <input type="number" class="form-control" name="cost" id="" placeholder="Enter Cost" value="{{old('cost')}}" required/>
                            </div>
                            <div class="form-group">
                                <label for="">Retail Price</label>
                                <input type="number" class="form-control" name="retail_price" value="{{old('retail_price')}}" placeholder="Enter Retail Price"
                                       required/>
                            </div>

                            <div class="form-group">
                                <label for="">Minimum Price</label>
                                <input type="text" class="form-control" name="min_price" value="{{old('min_price')}}" placeholder="Enter Minimum Price"/>
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
                            <div class="form-group">
                                <label>Has Serial</label>
                                <input id="" type="checkbox" name="has_serial_number" value="">
                            </div>

                        </div>
                    </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success shadow rounded font-weight-bold float-right">Add New</button>
                </div>
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


    <h4>Products @if(auth()->user()->hasPermissionTo('product-create')) <button
                class="btn btn-success float-right shadow rounded font-weight-bold"
                data-toggle="modal" data-target="#exampleModalCenter">Add Product</button>
        @endif
    </h4>


    <br>
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    <div class="card shadow rounded">
        <div class="card-body">
            <div id="app" class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">UPC</th>
                        <th scope="col">Cost</th>
                        <th scope="col">Category</th>
                        <th scope="col">Department</th>
                        <th scope="col">Active</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $count = 1;
                    @endphp
                    @forelse(\App\Models\Product::all() as $item)
                        <tr>
                            <td>{{$count++}}</td>
                            <td><a href="{{route('products.edit',$item->id)}}">{{$item->name}}</a></td>
                            <td>{{$item->description}}</td>
                            <td>{{$item->UPC}}</td>
                            <td>{{$item->cost}}</td>
                            <td>{{$item->category->name}}</td>
                            <td>{{$item->department->name}}</td>
                            <td>
                                <span class="{!! $item->active == 0 ? 'badge badge-danger' : 'badge badge-success' !!}">{!!$item->active == 0 ? 'IN-ACTIVE' : 'ACTIVE'  !!}</span>
                            </td>
                            <td>
                                <div style="display: flex;margin:4px">
                                    @if(auth()->user()->hasPermissionTo('product-edit'))
                                        <a class="btn btn-info mr-1" href="{{route('products.edit',$item->id)}}"><i
                                                    class="fa fa-pen"></i></a>
                                    @endif
                                    @if(auth()->user()->hasPermissionTo('product-delete'))
                                        <form action="{{route('products.destroy',$item->id)}}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-danger ml-1"><i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <p>No Products</p>
                    @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script src="{{asset('/js/app.js')}}"></script>
@endsection