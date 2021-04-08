@extends('adminlte::page')

@section('title','All Stores')

@section('content')
    <div class="container">
        <div class="row" style="align-items: center">
            <div class="col-lg-3 col-md-3 col-sm-3">
                <a href="{{route('sales.index')}}" style="">
                    <div class="card shadow-lg bg-white rounded"
                         style="height: 120px; background-image: linear-gradient(87deg, #3c3f72  0%, #8187ec  100%) !important; border-radius: .375rem; ">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col">
                                    <i class="fas fa-money-bill-wave text-light" style="font-size: 70px"></i>
                                </div>
                                <div class="col">
                                    <h5 class="mt-1 text-light">Customer Sale</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
                <a href="{{route('purchase.index')}}" style="color: #fff;">
                    <div class="card shadow-lg bg-white rounded"
                         style="height: 120px; background-image: linear-gradient(87deg, #ef3232  0%, #ff6666  100%) !important; border-radius: .375rem;">
                        <div class="card-body text-center">
                            <div class="row">
                                <div class="col">
                                    <i class="fas fa-people-carry text-light" style="font-size: 70px"></i>
                                </div>
                                <div class="col">
                                    <h4 class="mt-3 text-light">Returns</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3">
                <a href="{{route('finance.index')}}" style="color: #fff;">
                    <div class="card shadow-lg bg-white rounded"
                         style="height: 120px; background-image: linear-gradient(87deg, #6da252 0%, #b8d3a9 100%); border-radius: .375rem;">
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
                    <div class="card shadow-lg bg-white rounded"
                         style="height: 120px; background-image: linear-gradient(87deg, #2980B9 0%, #6DD5FA 100%); border-radius: .375rem;">
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

        <div class="container">
            <div class="card shadow rounded">
                <div class="card-body">
                    <h1>Store <a class="float-right btn btn-primary" href="{{route("stores.create")}}">Create</a></h1>
                    @if(auth()->user()->hasPermissionTo('store-edit'))

                    @endif
                    <br>
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                {{--<th scope="col">Location</th>--}}
                                {{--<th scope="col">Description</th>--}}
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @forelse(\App\Models\Store::all() as $item)
                                <tr>
                                    <td>{{$count++}}</td>
                                    <td><a href="{{route('stores.edit',$item->id)}}">{{$item->name}}</a></td>
                                    {{--<td>{{$item->location}}</td>--}}
                                    {{--<td>{{$item->description}}</td>--}}
                                    <td>
                                        <span class="{!! $item->active == 0 ? 'badge badge-danger' : 'badge badge-success' !!}">{!!$item->active == 0 ? 'IN-ACTIVE' : 'ACTIVE'  !!}</span>
                                    </td>

                                    <td>
                                        <div style="display: flex;margin:4px">
                                            @if(auth()->user()->hasPermissionTo('store-edit'))
                                                <a class="btn btn-info" href="{{route('stores.edit',$item->id)}}"><i
                                                            class="fa fa-pen mr-1"></i></a>
                                            @endif
                                            @if(auth()->user()->hasPermissionTo('store-delete'))

                                                {{--<form action="{{route('stores.destroy',$item->id)}}" method="POST">--}}
                                                {{--@method('DELETE')--}}
                                                {{--@csrf--}}
                                                {{--<button type="submit" class="btn btn-danger ml-1"><i class="fa fa-trash"></i>--}}
                                                {{--</button>--}}
                                                {{--</form>--}}
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <p>No Stores</p>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>


    </div>
@endsection
