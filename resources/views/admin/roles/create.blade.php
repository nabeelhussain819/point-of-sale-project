@extends('adminlte::page')

@section('title','Create A New Role')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title"> <h1>Create A New Role</h1></div>
        </div>
        <div class="card-body">
            <form action="{{route('roles.store')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="name" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Role Name" required>
                </div>
                <div class="form-group">
                    <label>Permissions</label>
                    <select name="permission" class="form-control">
                        <option value="">Please Select Permission</option>
                       @foreach($permission as $item)
                           <option value="{{$item->id}}">{{$item->name}}</option>
                        @endforeach
                    </select>
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
