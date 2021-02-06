@extends('adminlte::page')

@section('title','Create A New User')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title"> <h1>Edit {{$user->name}}</h1></div>
        </div>
        <div class="card-body">
            <form action="{{route('users.update',$user->id)}}" method="POST">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="name" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{$user->name}}" placeholder="Enter User Name" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Email</label>
                    <input type="email" class="form-control" name="email" id="exampleInputPassword1" placeholder="Enter Email" value="{{$user->email}}" required>
                </div>
{{--                <div class="form-group">--}}
{{--                    <label for="exampleInputPassword1">Password</label>--}}
{{--                    <input type="text" class="form-control" name="password" placeholder="Enter Password" {{}} required />--}}
{{--                </div>--}}

                <div class="form-group ">
                    <label>Status</label>
                    <select name="role" class="form-control" required>
                        <option value="">Please Select Role</option>
                        @foreach($role as $item)
                            <option value="{{$item->name}}">{{$item->name}}</option>
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
