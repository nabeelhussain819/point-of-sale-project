@extends('adminlte::page')

@section('title','Edit Store')

@section('content')
<div class="row">

    <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="card-title"> <h1>Store Details</h1></div>
                    <button type="button" class="btn btn-primary float-right" style="margin:10px" data-toggle="modal" data-target="#exampleModalCenter{{$store->id}}">
                        Assign Store
                    </button>
                    @include('partials.modal',['modalId' => $store->id, 'id' => $store->id,
                    'name' => 'store_id','route' =>'addusertostore' ,'nameO' => $store->name, 'description' => $store->description])
                </div>
                <div class="card-body">
                    <form action="{{route('stores.update',$store->id)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="exampleInputEmail1">Store Name</label>
                            <input type="name" value="{{$store->name}}" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Store Name" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Location</label>
                            <input type="text" value="{{$store->location}}" class="form-control" name="location" id="exampleInputPassword1" placeholder="Enter Location" required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Description</label>
                            <textarea type="text" class="form-control" {{$store->description}} name="description" id="exampleInputPassword1" placeholder="Enter Description" required>{{$store->description}}</textarea>
                        </div>
        
                        {{-- <div class="form-group">
                            <label>Select User</label>
                            <select name="user_id" class="form-control">
                                <option value="">Please Select User</option>
                                @foreach(\App\Models\User::where('id','!=',auth()->id())->get() as $user)
                                    <option value="{{$user->id}}" {{$user->id == $user_id ? 'selected' : ''}}>{{$user->name}} <span class="badge badge-primary">({{$user->getRoleNames()->first()}})</span></option>
                                @endforeach
                            </select>
                            
                        </div>
        
                        <div class="form-group">
                            <label>Select Role</label>
                            <select name="role_id" class="form-control">
                                <option value="">Please Select Role</option>
                                @foreach(\Spatie\Permission\Models\Role::where('id','!=',1)->get() as $item)
                                    <option value="{{$item->id}}" {{$item->id == $role_id ? 'selected' : ''}}>{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div> --}}
        
                        <div class="form-group ">
                            <label>Status</label>
                            <select name="active" class="form-control" required>
                                <option value="0" {{$store->active == 0 ? 'selected' : ''}}>IN-ACTIVE</option>
                                <option value="1" {{$store->active == 1 ? 'selected' : ''}}>ACTIVE</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" name="edit_store">Submit</button>
                    </form>
                </div>    
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
    <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="container">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        User Assigned to this store
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Assigned As</th>
                                    <th scope="col">Active</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                            $count = 1;
                            @endphp
                        @forelse($storeOfUser as $item)
                        <tr>
                            <td>{{$count++}}</td>
                                <td>
                                    {{$item->user->name}}
                                    <span class="badge badge-primary">{{$item->user->roles->pluck('name')->first()}}</span>
                                </td>
                                <td>{{$item->user->email}}</td>
                                <td>{{$item->user->userDetail->pluck('phone')->first() ?? 'No Number Added'}}</td>
                                <td>{{$item->role->name}}</td>
                                <td><span class="{!! $item->store->active == 0 ? 'badge badge-danger' : 'badge badge-success' !!}">{!!$item->store->active == 0 ? 'IN-ACTIVE' : 'ACTIVE'  !!}</span></td>
                                <td>
                                    <div style="display: flex">
                                        <form action="{{route('user-store.destroy',$item->id)}}" method="POST">
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
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
