@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content')
    <div class="container">
        <div class="container">
            <div class="row" style="align-items: center">
                <div class="col-lg-3 col-md-3 col-sm-3">
                    <a href="{{route('sales.index')}}" style="">
                            <div class="card shadow-lg bg-white rounded" style="height: 120px; background-image: linear-gradient(87deg, #3c3f72  0%, #8187ec  100%) !important; border-radius: .375rem; ">
                            <div class="card-body text-center">
                                <div class="row">
                                    <div class="col">
                                        <i class="fas fa-money-bill-wave text-light" style="font-size: 70px"></i>
                                    </div>
                                    <div class="col">
                                        <h4 class="mt-1 text-light" >Customer Sale</h4>
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
                                        <h4 class="mt-3 text-light" >Returns</h4>
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
                                        <h4 class="mt-0 text-light">Layaway/In Store Finace</h4>
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
                                        <i class="fas fa-tools text-light" style="font-size: 70px"></i>
                                    </div>
                                    <div class="col">
                                        <h4 class="mt-3 text-light">Repairs</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
{{--                <div class="col-lg-3 col-md-3 col-sm-3">--}}
{{--                    <a href="{{route('inventory.index')}}" style="">--}}
{{--                        <div class="card shadow-lg bg-white rounded" style="height: 120px; background-image: linear-gradient(90deg, #4D5D53 0%, #4F7942 20%, #8FBC8B 100%);">--}}
{{--                            <div class="card-body text-center">--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col">--}}
{{--                                        <i class="fas fa-list-alt text-light" style="font-size: 70px"></i>--}}
{{--                                    </div>--}}
{{--                                    <div class="col">--}}
{{--                                        <h4 class="mt-1 text-light" >Item Search</h4>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                </div>--}}
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

        <div class="container">
            <h1>Stores </h1>
            <br>
            <div class="card shadow rounded">
                <div class="card-body">
                    <table class="table">
                        <thead class="thead">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Full Name</th>
                            <th scope="col">City/state/zip</th>
                            <th scope="col">Location ID</th>
                            <th scope="col">Primary Phone</th>
                            <th scope="col">SecondaryPhone</th>
                            <th scope="col">Fax</th>
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
                                <td>{{$item->full_name}}</td>
                                <td>{{$item->city}} / {{$item->state}} / {{$item->zip}}</td>
                                <td>{{$item->code}}</td>
                                <td>{{$item->primary_phone}}</td>
                                <td>{{$item->contact_info}}</td>
                                <td>{{$item->fax}}</td>
                                <td><span class="{!! $item->active == 0 ? 'badge badge-danger' : 'badge badge-success' !!}">{!!$item->active == 0 ? 'IN-ACTIVE' : 'ACTIVE'  !!}</span></td>

                                <td>
                                    <div style="display: flex;margin:4px">
                                        <a class="btn btn-info" href="{{route('stores.edit',$item->id)}}"><i class="fa fa-pen"></i></a>
                                        <form action="{{route('stores.destroy',$item->id)}}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <a href="{{route('stores.create')}}">No Stores, Please Create One</a>
                        @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
            <a class="btn btn-success m-1 shadow-lg rounded float-right" href="{{route('stores.create')}}">Add Store</a>

        </div>
    </div>
@endsection
