@extends('adminlte::page')

@section('title','Edit Role')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title"> <h1>Edit {{$role->name}} Role</h1></div>
        </div>
        <div class="card-body">
            <form action="{{route('roles.update',$role->id)}}" method="POST">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="name" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Role Name" value="{{$role->name}}" required>
                </div>
                <div class="form-group">
                    <label>Permissions</label>
                    <br>
                    @foreach(\Spatie\Permission\Models\Permission::all() as $item)
                        <label class="checkbox-inline" style="font-size: 12px; padding: 5px;"><span class="badge badge-primary">{{$item->name}}</span> </label>
                        <input type="checkbox" name="permission[]" value="{{$item->id}}" />
                    @endforeach
                </div>
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
