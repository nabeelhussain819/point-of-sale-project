@extends('adminlte::page')

@section('title','Inventory Managment')

@section('content')
    <div class="container">
        <div class="row" style="align-items: center">
            <div class="col-lg-3 col-md-3 col-sm-3">
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
            <div class="col-lg-3 col-md-3 col-sm-3">
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
            <div class="col-lg-3 col-md-3 col-sm-3">
                <a href="{{route('inventory.create')}}" style="color: #fff;">
                    <div class="card shadow-lg bg-white rounded" style="height: 120px; background-image: linear-gradient(87deg, #6da252 0%, #b8d3a9 100%); border-radius: .375rem;">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col">
                                    <i class="fas fa-cubes text-light" style="font-size: 70px"></i>
                                </div>
                                <div class="col">
                                    <h4 class="text-light mt-3" >Add Stock</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
                <a href="{{route('inventory.index')}}" style="">
                    <div class="card shadow-lg bg-white rounded" style="height: 120px; background-image: linear-gradient(87deg, #2980B9 0%, #6DD5FA 100%); border-radius: .375rem;">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col">
                                    <i class="fas fa-list-alt text-light" style="font-size: 70px"></i>
                                </div>
                                <div class="col">
                                    <h4 class="text-light mt-1" >View/Edit Stock</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <br>
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
    </div>
    <div class="container">
        @if(auth()->user()->hasPermissionTo('inventory-create'))
            <h4>All Inventories</h4>
        @endif
        <br>
            <div class="card shadow rounded">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Vendor Name</th>
                                <th scope="col">Phone</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @forelse($inventories as $item)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->vendor->name }}</td>
                                    <td>
                                        <div style="display: flex">
                                            @if(auth()->user()->hasPermissionTo('inventory-edit'))

                                                <a class="btn btn-info"
                                                   href="{{ route('inventory.edit',$item->id) }}"><i
                                                            class="fa fa-pen"></i></a>
                                            @endif
                                            @if(auth()->user()->hasPermissionTo('inventory-delete'))

                                                <form action="{{ route('inventory.destroy',$item->id) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <p>No Inventories</p>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
@endsection
