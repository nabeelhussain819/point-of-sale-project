@extends('adminlte::page')

@section('title','Create A New Role')
@section('content')
    <div class="container">
        <h4>Create A New Role</h4>
        <div class="card shadow rounded">

            <div class="card-body">
                <form action="{{route('roles.store')}}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">Name</label>
                        <input type="name" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Role Name" required>
                    </div>
                    <div class="form-group">
                        <label>Permissions</label>
                        <br>
                        @foreach($permission as $item)
                            <label class="checkbox-inline" style="font-size: 12px; padding: 5px;"><span class="badge badge-primary">{{$item->name}}</span> </label>
                            <input type="checkbox" name="permission[]" value="{{$item->id}}"/>
                        @endforeach
                    </div>
                    <button type="submit" class="btn btn-success float-right font-weight-bold">Add New</button>
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

    </div>
@endsection
