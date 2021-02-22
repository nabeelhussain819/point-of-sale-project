@extends('adminlte::page')

@section('title','All Roles')

@section('content')
    <div class="container">
        <h4>Roles @if(auth()->user()->hasPermissionTo('role-create'))
                <a class="btn btn-success float-right shadow rounded font-weight-bold"
                   href="{{route('roles.create')}}">Add New Role</a>
            @endif
        </h4>
        <br>
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="card shadow rounded">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead class="thead">
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
                                        <span class="badge badge-primary">{{$p->name}}</span>
                                    @endforeach
                                </td>
                                <td>{{$item->created_at}}</td>
                                <td>
                                    <div style="display: flex">
                                        @if(auth()->user()->hasPermissionTo('role-edit'))
                                            <a class="btn btn-info" href="{{route('roles.edit',$item->id)}}"><i
                                                        class="fa fa-pen"></i></a>
                                        @endif
                                        @if(auth()->user()->hasPermissionTo('role-delete'))
                                            <form action="{{route('roles.destroy',$item->id)}}" method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <p>No Roles</p>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
