@extends('adminlte::page')

@section('title','Edit Role')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title"> <h1>Create A New Role</h1></div>
        </div>
        <div class="card-body">
            <form action="{{route('roles.update',$role->id)}}" method="POST">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="name" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Role Name" value="{{$role->name}}" required>
                </div>
                <select name="permission" class="form-control">
                    @foreach($rolePermissions as $item)
                        <option value="{{$item->id}}">{{$item->name}}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
        @if (isset($errors) && count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endsection
