@extends('adminlte::page')

@section('title','All Stores')

@section('content')
    <div class="container">
        <h1>Stores</h1>
        <a class="btn btn-success" href="{{route('stores.create')}}">Add Store</a>
        <br>
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Location</th>
                <th scope="col">Description</th>
                <th scope="col">Active</th>
                <th scope="col">Assigned To</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @php
                $count = 1;
            @endphp
            @forelse($store as $item)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$item->store->name}}</td>
                    <td>{{$item->store->location}}</td>
                    <td>{{$item->store->description}}</td>
                    <td><span class="{!! $item->store->active == 0 ? 'badge badge-danger' : 'badge badge-success' !!}">{!!$item->store->active == 0 ? 'IN-ACTIVE' : 'ACTIVE'  !!}</span></td>
                    <td>
                        {{$item->user->name}}
                        <span class="badge badge-primary">{{$item->user->roles->pluck('name')}}</span>
{{--                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter{{$item->id}}">--}}
{{--                            Assign Store--}}
{{--                        </button>--}}
{{--                        @include('partials.modal',['modalId' => $item->id, 'id' => $item->id,'name' => 'store_id','route' =>'addusertostore'])--}}
                    </td>
                    <td>
                        <div style="display: flex">
                            <a class="btn btn-info" href="{{route('stores.edit',$item->store->id)}}"><i class="fa fa-pen"></i></a>
                            <form action="{{route('stores.destroy',$item->store->id)}}" method="POST">
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
@endsection
