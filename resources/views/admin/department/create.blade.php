@extends('adminlte::page')

@section('title','Create a new Department')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title"> <h1>Create a new Department</h1></div>
        </div>
        <div class="card-body">
            <form action="{{route('departments.store')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" name="name" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Name" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Full Name</label>
                    <input type="text" class="form-control" name="full_name" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Reference</label>
                    <input type="text" class="form-control" name="reference" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="#Reference" required>
                </div>
                <div class="form-group ">
                    <label>Status</label>
                    <select name="active" class="form-control" required>
                        <option value="0">IN-ACTIVE</option>
                        <option value="1">ACTIVE</option>
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
