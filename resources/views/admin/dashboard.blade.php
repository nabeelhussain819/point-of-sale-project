@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content_header')
    <h1>Dashboard</h1>
@stop
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"></div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <h1>Stores</h1>
            <a class="btn btn-success" href="{{route('stores.create')}}">Add Store</a>
            <br>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Location</th>
                    <th scope="col">Description</th>
                    <th scope="col">Active</th>
                    <th scope="col">Assign</th>
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
                    <td>{{$item->name}}</td>
                    <td>{{$item->location}}</td>
                    <td>{{$item->description}}</td>
                    <td><span class="{{$item->active == 1 ? 'badge badge-secondary' : 'badge badge-success'}}">{{$item->active == 1 ? 'ACTIVE' : 'IN-ACTIVE' }}</span></td>
                    <td>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter{{$item->id}}">
                            Assign Store
                        </button>
                        @include('partials.modal',['modalId' => $item->id, 'id' => $item->id,'name' => 'store_id','route' =>'addusertostore'])
                    </td>
                    <td>
                        <div style="display: flex">
                        <a class="btn btn-info" href="{{route('stores.edit',$item->id)}}">Edit</a>
                        <form action="{{route('stores.destroy',$item->id)}}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="btn btn-danger">DELETE</button>
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
@endsection
