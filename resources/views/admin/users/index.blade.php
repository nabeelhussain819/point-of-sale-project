@extends('adminlte::page')

@section('title','All Users')

@section('content')
    <div class="container">
        <h1>Assigned Users</h1>
        <a class="btn btn-success" href="{{route('users.create')}}">Add User</a>
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
                <th scope="col">Email</th>
                <th scope="col">Store Assigned</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @php
                $count = 1;
            @endphp
            @forelse($users as $item)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$item->user->name}}</td>
                    <td>{{$item->user->email}}</td>
                    <td>{{$item->store->name}}</td>
                    <td>
                        <div style="display: flex">
                            <a class="btn btn-info" href="{{route('users.edit',$item->user->id)}}"><i class="fa fa-pen"></i></a>
                            <form action="{{route('users.destroy',$item->user->id)}}" method="POST">
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
        {{$users->links()}}
    </div>
    <div class="container">
        <h1>All Users</h1>
        <br>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @php
                $count = 1;
            @endphp
            @forelse($user as $item)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->email}}</td>
                    <td>
                        <div style="display: flex">
                            <a class="btn btn-info" href="{{route('users.edit',$item->id)}}"><i class="fa fa-pen"></i></a>
                            <form action="{{route('users.destroy',$item->id)}}" method="POST">
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
        {{$user->links()}}
    </div>
@endsection
