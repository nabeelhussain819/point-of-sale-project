@extends('adminlte::page')

@section('title','Product Management')

@section('content')
    <div class="container">
        <div class="container">
            <div class="container">
                <div class="row" style="align-items: center">
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <a href="{{route('categories.index')}}" style="">
                            <div class="card shadow-lg bg-white rounded"
                                 style="height: 120px; background-image: linear-gradient(87deg, #3c3f72  0%, #8187ec  100%) !important; border-radius: .375rem; ">
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col">
                                            <i class="fas fa-align-center text-light" style="font-size: 70px"></i>
                                        </div>
                                        <div class="col">
                                            <h5 class="mt-2 text-light">Add/Edit Category</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <a href="{{route('departments.index')}}" style="color: #fff;">
                            <div class="card shadow-lg bg-white rounded"
                                 style="height: 120px; background-image: linear-gradient(87deg, #ef3232  0%, #ff6666  100%) !important; border-radius: .375rem;">
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col">
                                            <i class="fas fa-building text-light" style="font-size: 70px"></i>
                                        </div>
                                        <div class="col">
                                            <h5 class="mt-2 text-light">Add/Edit Department</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <a href="{{route('products.create')}}" style="color: #fff;">
                            <div class="card shadow-lg bg-white rounded"
                                 style="height: 120px; background-image: linear-gradient(87deg, #6da252 0%, #b8d3a9 100%); border-radius: .375rem;">
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col">
                                            <i class="fas fa-cubes text-light" style="font-size: 70px"></i>
                                        </div>
                                        <div class="col">
                                            <h5 class="mt-2 text-light">Add/Edit Product</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3">
                        <a href="{{route('stock-bin.index')}}" style="">
                            <div class="card shadow-lg bg-white rounded"
                                 style="height: 120px; background-image: linear-gradient(87deg, #2980B9 0%, #6DD5FA 100%); border-radius: .375rem;">
                                <div class="card-body text-center">
                                    <div class="row">
                                        <div class="col">
                                            <i class="fas fa-layer-group text-light" style="font-size: 70px"></i>
                                        </div>
                                        <div class="col">
                                            <h5 class="mt-2 text-light">Add/Edit Stock Bins</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
