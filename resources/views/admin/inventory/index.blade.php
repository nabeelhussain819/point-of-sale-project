@extends('adminlte::page')

@section('title','Inventory Managment')

@section('content')
    <div class="container">

            <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <a href="{{route('products.index')}}" style="">
                    <div class="card shadow-lg bg-white rounded" style="height: 120px; background-image: linear-gradient(87deg, #3c3f72  0%, #8187ec  100%) !important; border-radius: .375rem;">
                    <div class="card-body text-center">
                        <div class="row">
                            <div class="col">
                                <i class="fab fa-product-hunt text-light" style="font-size: 70px"></i>
                            </div>
                            <div class="col">
                                <h4 class="text-light mt-1" style="margin-top: 8px;color: #fff;">Product Manager</h4>
                            </div>
                        </div>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <a href="{{route('inventory.create')}}" style="">
                    <div class="card shadow-lg bg-white rounded" style="height: 120px; background-image: linear-gradient(87deg, #2980B9 0%, #6DD5FA 100%); border-radius: .375rem;">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col">
                                    <i class="fas fa-list-alt text-light" style="font-size: 70px"></i>
                                </div>
                                <div class="col">
                                    <h4 class="text-light mt-4" >View Stock</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <a href="{{route('vendors.index')}}" style="color: #fff;">
                    <div class="card shadow-lg bg-white rounded" style="height: 120px; background-image: linear-gradient(87deg, #ef3232  0%, #ff6666  100%) !important; border-radius: .375rem;">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col">
                                    <i class="fas fa-people-carry text-light" style="font-size: 70px"></i>
                                </div>
                                <div class="col">
                                    <h4 class="text-light mt-1" >Add/Edit Vendor</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            </div>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4">
                <a href="{{route('transfer.create')}}" style="color: #fff;">
                    <div class="card shadow-lg bg-white rounded" style="height: 120px; background-image: linear-gradient(87deg, #3c3f72  0%, #8187ec  100%); border-radius: .375rem;">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col">
                                    <i class="fas fa-cubes text-light" style="font-size: 70px"></i>
                                </div>
                                <div class="col">
                                    <h4 class="text-light mt-3" >Transfer Stock</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <a href="{{route('purchase-order.index')}}" style="color: #fff;">
                    <div class="card shadow-lg bg-white rounded" style="height: 120px;  background-image: linear-gradient(87deg, #2980B9 0%, #6DD5FA 100%); border-radius: .375rem;">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col">
                                    <i class="fas fa-money-bill-wave text-light" style="font-size: 70px"></i>
                                </div>
                                <div class="col">
                                    <h4 class="text-light mt-2" >Purchase Order</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4">
                <a href="{{route('reconciliation.index')}}" style="color: #fff;">
                    <div class="card shadow-lg bg-white rounded" style="height: 120px;background-image: linear-gradient(87deg, #ef3232  0%, #ff6666  100%); border-radius: .375rem;">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col">
                                    <i class="fas fa-cubes text-light" style="font-size: 70px"></i>
                                </div>
                                <div class="col">
                                    <h4 class="text-light mt-1" >Stock Counting / Recollection</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

@endsection
