@extends('adminlte::page')

@section('title','Edit Category')
@section('content')
    <div class="card">
        <div class="card-header">
            <div class="card-title"> <h1>Edit {{$category->name}}</h1></div>

        </div>
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="card-body">
            <form action="{{route('categories.update',$category->id)}}" method="POST">
                @method('PUT')
                @csrf
                <div class="form-group">
                    <label for="exampleInputEmail1">Name</label>
                    <input type="text" class="form-control" name="name" value="{{$category->name}}" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Name" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Full Name</label>
                    <input type="text" class="form-control" name="full_name" id="exampleInputEmail1"  value="{{$category->full_name}}"aria-describedby="emailHelp" placeholder="Full Name" required>
                </div>
                <div class="form-group">
                    <label for="exampleInputEmail1">Reference</label>
                    <input type="text" class="form-control" name="reference" id="exampleInputEmail1" value="{{$category->reference}}" aria-describedby="emailHelp" placeholder="#Reference" required>
                </div>
                <div class="form-group ">
                    <label>Status</label>
                    <select name="active" class="form-control" required>
                        <option value="0" {{$category->active == 0 ? 'selected' : ''}}>IN-ACTIVE</option>
                        <option value="1" {{$category->active == 1 ? 'selected' : ''}}>ACTIVE</option>
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
