@extends('adminlte::page')

@section('title','Edit Store')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title"> <h1>Edit A Store</h1></div>
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

                <div class="form-group ">
                    <label>Status</label>
                    <select name="active" class="form-control" required>
                        <option value="1" {{$store->active == 1 ? 'selected' : ''}}>ACTIVE</option>
                        <option value="0">IN-ACTIVE</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection
