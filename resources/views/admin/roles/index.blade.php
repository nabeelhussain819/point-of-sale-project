@extends('adminlte::page')

@section('title','All Roles')

@section('content')
    <div class="container">
        <h1>Roles</h1>
        <a class="btn btn-success" href="{{route('roles.create')}}">Add New Role</a>
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
                <th scope="col">Permissions</th>
                <th scope="col">Created At</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @php
                $count = 1;
            @endphp
            @forelse($roles as $item)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$item->name}}</td>
                    <td>@foreach($item->permissions as $p)
                            {{$p->name}}
                        @endforeach
                        </td>
                    <td>{{$item->created_at}}</td>
                    <td>
                        <div style="display: flex">
                            <a class="btn btn-info" href="{{route('roles.edit',$item->id)}}"><i class="fa fa-pen"></i></a>
                            <form action="{{route('roles.destroy',$item->id)}}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <a href="{{route('roles.create')}}">No Roles, Please Create One</a>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
