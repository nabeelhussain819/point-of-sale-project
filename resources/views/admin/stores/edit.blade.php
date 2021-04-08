@extends('adminlte::page')

@section('title','Edit Store')

@section('content')
<div class="row">

    <div class="col-lg-6 col-md-6 col-sm-6">
        <div class="container">
            <div class="card shadow rounded">
                <div class="card-header">
                    @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="card-title"> <h1>Store Details</h1></div>
                    <button type="button" class="btn btn-primary float-right mt-2" data-toggle="modal" data-target="#exampleModalCenter{{$store->id}}">
                        Assign User
                    </button>
                    @include('partials.modal',['modalId' => $store->id, 'id' => $store->id,
                    'name' => 'store_id','route' =>'addusertostore' ,'nameO' => $store->name,
                     'description' => $store->description])
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
                            <label for="exampleInputPassword1">City</label>
                            <input type="text"  value="{{$store->city}}" class="form-control" name="city" placeholder="City"  value="{{old('city')}}"required>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">State</label>
                            <input type="text"  value="{{$store->state}}" class="form-control" name="state" placeholder="State" value="{{old('state')}}" required>
                        </div>


                        <div class="form-group">
                            <label>Primary Phone</label>
                            <input type="text"  value="{{$store->primary_phone}}" class="form-control" name="primary_phone" value="{{old('primary_phone')}}" placeholder="Primary Phone"/>
                        </div>
                        <div class="form-group ">
                            <label>Status</label>
                            <select name="active" class="form-control" required>
                                <option value="0" {{$store->active == 0 ? 'selected' : ''}}>IN-ACTIVE</option>
                                <option value="1" {{$store->active == 1 ? 'selected' : ''}}>ACTIVE</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success float-right font-weight-bold" name="edit_store">Edit</button>
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
            <div class="card shadow rounded">
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
                                    {{--<span class="badge badge-primary">{{$item->user->roles->pluck('name')->first()}}</span>--}}
                                </td>
                                <td>{{$item->user->email}}</td>
                                <td>{{$item->user->userDetail->pluck('phone')->first() ?? 'No Number Added'}}</td>

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
                            <p>No User Assigned to this store</p>
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
