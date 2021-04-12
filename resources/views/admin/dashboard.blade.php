@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content')

    <div class="container">
        <div class="container">
            <div class="row" style="align-items: center">
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <a href="{{route('sales.create')}}" style="">
                            <div class="card shadow-lg bg-white rounded" style="height: 120px; background-image: linear-gradient(87deg, #3c3f72  0%, #8187ec  100%) !important; border-radius: .375rem; ">
                            <div class="card-body text-center">
                                <div class="row">
                                    <div class="col">
                                        <i class="fas fa-money-bill-wave text-light" style="font-size: 70px"></i>
                                    </div>
                                    <div class="col">
                                        <h5 class="mt-1 text-light" >Customer Sale</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <a href="{{route('purchase.index')}}" style="color: #fff;">
                        <div class="card shadow-lg bg-white rounded" style="height: 120px; background-image: linear-gradient(87deg, #ef3232  0%, #ff6666  100%) !important; border-radius: .375rem;">
                            <div class="card-body text-center">
                                <div class="row">
                                    <div class="col">
                                        <i class="fas fa-people-carry text-light" style="font-size: 70px"></i>
                                    </div>
                                    <div class="col">
                                        <h4 class="mt-3 text-light" >Returns</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <a href="{{route('finance.index')}}" style="color: #fff;">
                        <div class="card shadow-lg bg-white rounded" style="height: 120px; background-image: linear-gradient(87deg, #6da252 0%, #b8d3a9 100%); border-radius: .375rem;">
                            <div class="card-body text-center">
                                <div class="row">
                                    <div class="col">
                                        <i class="fas fa-cubes text-light" style="font-size: 70px"></i>
                                    </div>
                                    <div class="col">
                                        <h5 class="mt-0 text-light">Layaway/In Store Finace</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <a href="{{route('repair.index')}}" style="">
                        <div class="card shadow-lg bg-white rounded" style="height: 120px; background-image: linear-gradient(87deg, #2980B9 0%, #6DD5FA 100%); border-radius: .375rem;">
                            <div class="card-body text-center">
                                <div class="row">
                                    <div class="col">
                                        <i class="fas fa-tools text-light" style="font-size: 70px"></i>
                                    </div>
                                    <div class="col">
                                        <h5 class="mt-3 text-light">Repairs</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        <div class="row justify-content-center">
            <div class="col-md-8">

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
            </div>
        </div>
        </div>
    </div>
@endsection
