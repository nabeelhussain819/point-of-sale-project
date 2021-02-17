@extends('adminlte::page')

@section('title','Create A New User')
@section('content')
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <h1>Create A New User</h1>
        </div>
    </div>
    <div class="card-body">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <h4>Personal Information</h4>
                    <div class="form-group">
                        <label for="">First Name</label>
                        <input type="text" class="form-control" value="{{old('first_name')}}" name="first_name" placeholder="Enter First Name"
                            required />
                    </div>
                    <div class="form-group">
                        <label for="">Last Name</label>
                        <input type="text" class="form-control" value="{{old('last_name')}}" name="last_name" placeholder="Enter Last Name" required/>
                    </div>
                    <div class="form-group">
                        <label for="">Full Name</label>
                        <input type="name" class="form-control" name="name" value="{{old('name')}}" id="" aria-describedby="emailHelp"
                            placeholder="Enter Full Name" required />
                    </div>
                    <div class="form-group">
                        <label for="">Phone Number</label>
                        <input type="name" class="form-control" name="phone" value="{{old('phone')}}" id="" aria-describedby="emailHelp"
                            placeholder="Enter Phone Number" required/>
                    </div>
                    <div class="form-group">
                        <label for="">Gender</label>
                        <select name="gender" id="" class="form-control">
                            <option value="0">Male</option>
                            <option value="1">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">DOB</label>
                        <input type="date" class="form-control" name="DOB" value="{{old('DOB')}}" id="" aria-describedby="emailHelp"
                            placeholder="Enter Phone Number" required/>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <h4>Login Information</h4>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" class="form-control" name="email" value="{{old('email')}}" id="" placeholder="Enter Email" required />
                    </div>
                    <div class="form-group">
                        <label for="">Driver License</label>
                        <input type="text" class="form-control" name="driver_license" value="{{old('driver_license')}}" placeholder="Enter Driver License"/>
                    </div>
                    <div class="form-group">
                        <label for="">State </label>
                        <input type="text" class="form-control" name="state" value="{{old('state')}}" placeholder="Enter State"/>
                    </div>
                    <div class="form-group">
                        <label for="">Active</label>
                        <select name="active" id="" class="form-control">
                            <option value="0">IN-ACTIVE</option>
                            <option value="1">ACTIVE</option>
                        </select>
                    </div>
                    <div class="form-group ">
                        <label>Select Role</label>
                        @include('partials.roles_lookup')
                    </div>
                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                </div>
            </div>
        </form>
    </div>
    @if(isset($errors) && count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
@endsection
