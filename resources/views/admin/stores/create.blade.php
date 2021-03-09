@extends('adminlte::page')

@section('title','Create Store')

@section('content')
    <div class="container">
        <h4>New Store</h4>
        <div class="card shadow rounded">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">

                        <form action="{{route('stores.store')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Store Name</label>
                                <input type="name" class="form-control" name="name" placeholder="Enter Store Name" value="{{old('name')}}" required>
                            </div>
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" class="form-control" name="full_name" placeholder="Full Name" value="{{old('full_name')}}" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Location</label>
                                <input type="text" class="form-control" name="location" placeholder="Location" value="{{old('location')}}" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">City</label>
                                <input type="text" class="form-control" name="city" placeholder="Location"  value="{{old('city')}}"required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">State</label>
                                <input type="text" class="form-control" name="state" placeholder="Location" value="{{old('state')}}" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Zip</label>
                                <input type="text" class="form-control" name="zip" placeholder="Location" value="{{old('zip')}}" required>
                            </div>
                    </div>
                    <div class="col-md-6">

                        <div class="form-group">
                            <label>Timezone</label>
                            <input type="date" class="form-control" name="timezone" value="{{old('timezone')}}" placeholder="Timezone" required>
                        </div>

                        <div class="form-group">
                            <label>Primary Phone</label>
                            <input type="text" class="form-control" name="primary_phone" value="{{old('primary_phone')}}" placeholder="Primary Phone"/>
                        </div>
                        <div class="form-group">
                            <label>Secondary Phone</label>
                            <input type="text" class="form-control" name="contact_info"  value="{{old('contact_info')}}"  placeholder="Contact Info"/>
                        </div>
                        <div class="form-group">
                            <label>Fax</label>
                            <input type="text" class="form-control" name="fax"  value="{{old('fax')}}" placeholder="Fax"/>
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="active" class="form-control" required>
                                <option value="0">IN-ACTIVE</option>
                                <option value="1">ACTIVE</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Description</label>
                            <textarea type="text" class="form-control" name="description" placeholder="Description"
                                      required>
                                {{old('description')}}
                            </textarea>
                        </div>

                        <button type="submit" class="btn btn-success float-right font-weight-bold shadow-lg rounded">Add New</button>
                        </form>
                    </div>
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
@endsection
