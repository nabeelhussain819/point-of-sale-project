@extends('adminlte::page')

@section('title','Create A New Customer')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title"> <h1>Create A New Customer</h1></div>
        </div>
        <div class="card-body">
            <form action="{{route('customers.store')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="name" class="form-control" name="name" id="exampleInputEmail1" placeholder="Name" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Email</label>
                    <input type="email" class="form-control" name="email" id="exampleInputEmail1" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Phone</label>
                    <input type="name" class="form-control" name="phone" id="exampleInputEmail1" placeholder="Phone" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Telephone</label>
                    <input type="name" class="form-control" name="telephone" id="exampleInputEmail1" placeholder="Telephone" required>
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
