@extends('adminlte::page')

@section('title','Create A New User')
@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success" role="alert">
        {{ session('success') }}
    </div>
    @endif
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h4>Edit {{ $user->name }}</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update',$user->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <h4>Personal Information</h4>
                                <div class="form-group">
                                    <label for="">First Name</label>
                                    <input type="text" class="form-control" name="first_name"
                                        value="{{ $user->userDetail->pluck('first_name')->first() }}"
                                        placeholder="Enter First Name" required />
                                </div>
                                <div class="form-group">
                                    <label for="">Last Name</label>
                                    <input type="text" class="form-control" name="last_name"
                                        value="{{ $user->userDetail->pluck('last_name')->first() }}"
                                        placeholder="Enter Last Name" required />
                                </div>

                                <div class="form-group">
                                    <label for="">Phone Number</label>
                                    <input type="name" class="form-control" name="phone" id=""
                                        value="{{ $user->userDetail->pluck('phone')->first() }}"
                                        aria-describedby="emailHelp" placeholder="Enter Phone Number" required />
                                </div>
                                <div class="form-group">
                                    <label for="">Gender</label>
                                    <select name="gender" id="" class="form-control">
                                        <option value="">Please Select Gender</option>
                                        <option value="0"
                                            {{ $user->userDetail->pluck('gender')->first() == 0 ? 'selected' : '' }}>
                                            Male</option>
                                        <option value="1"
                                            {{ $user->userDetail->pluck('gender')->first() == 1 ? 'selected' : '' }}>
                                            Female</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="">DOB</label>
                                    <input type="date" class="form-control" name="DOB" id=""
                                        value="{{ $user->userDetail->pluck('DOB')->first() }}"
                                        aria-describedby="emailHelp" placeholder="Enter Phone Number" required />
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <h4>Login Information</h4>
                                <div class="form-group">
                                    <label for="">Email</label>
                                    <input type="email" class="form-control" value="{{ $user->email }}" name="email"
                                        id="" placeholder="Enter Email" required />
                                </div>
                                {{-- <div class="form-group">
                                    <label for="">Password</label>
                                    <input type="text" class="form-control" name="password" placeholder="Enter Password"
                                        required />
                                </div> --}}

                                <div class="form-group">
                                    <label for="">Driver License</label>
                                    <input type="text" class="form-control"
                                        value="{{ $user->userDetail->pluck('driver_license')->first() }}"
                                        name="driver_license" placeholder="Enter Driver License" />
                                </div>
                                <div class="form-group">
                                    <label for="">State </label>
                                    <input type="text" class="form-control"
                                        value="{{ $user->userDetail->pluck('state')->first() }}"
                                        name="state" placeholder="Enter State" />
                                </div>
                                <div class="form-group">
                                    <label for="">Active</label>
                                    <select name="active" id="" class="form-control">
                                        <option value="0"
                                            {{ $user->userDetail->pluck('active')->first() == 0 ? 'selected' : '' }}>
                                            IN-ACTIVE</option>
                                        <option value="1"
                                            {{ $user->userDetail->pluck('active')->first() == 1 ? 'selected' : '' }}>
                                            ACTIVE</option>
                                    </select>
                                </div>
                                <div class="form-group ">
                                    <label>Select Role</label>
                                    <select name="role_id" class="form-control">
                                    @foreach(\Spatie\Permission\Models\Role::where('id','!=',1)->get() as $item)
                                    <option value="{{$item->id}}" {{$item->id == $user->roles->pluck('id')->first() ? 'selected' : ''}}>{{$item->name}}</option>
                                    @endforeach
                                    </select>
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
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        <h4>Roles</h4>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{route('addroletouser')}}" method="POST">
                        @csrf
                        <div class="form-group" style="display: inline">
                       {{--//@todo optimize--}}
                                    @foreach(\Spatie\Permission\Models\Role::where('id','!=',1)->get() as $item)

                                    <input value="{{$item->id}}" {{ $user->getRolesChecked($item->id) }} type="checkbox" name="role_id[]" />
                                <label>
                                    {{$item->name}}
                                </label>
                                        {{-- <option value="{{$item->id}}" {{$item->id == $user->roles->pluck('id')->first() ? 'selected' : ''}}>{{$item->name}}</option> --}}
                                    @endforeach
                                {{-- </select> --}}
                        </div>
                        <input type="hidden" value="{{$user->id}}" name="user_id"/>
                        <button type="submit" class="btn btn-primary float-right" name="assign">Assign</button>
                    </form>
                </div>


            </div>
    </div>
</div>
</div>
@endsection
