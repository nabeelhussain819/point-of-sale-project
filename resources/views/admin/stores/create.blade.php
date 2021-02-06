@extends('adminlte::page')

@section('title','Create Store')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title"> <h1>Create A Store</h1></div>
        </div>
        <div class="card-body">
            <form action="{{route('stores.store')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1">Store Name</label>
                    <input type="name" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Store Name" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Location</label>
                    <input type="text" class="form-control" name="location" id="exampleInputPassword1" placeholder="Enter Location" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Description</label>
                    <textarea type="text" class="form-control" name="description" id="exampleInputPassword1" placeholder="Enter Description" required></textarea>
                </div>

                <div class="form-group ">
                    <label>Status</label>
                    <select name="active" class="form-control" required>
                        <option value="1">ACTIVE</option>
                        <option value="0">IN-ACTIVE</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
@endsection
