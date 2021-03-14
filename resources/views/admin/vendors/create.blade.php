@extends('adminlte::page')

@section('title','Create Vendor')
@section('content')
    <div class="container">
        <h4>New Vendor</h4>
        <div class="card">
            <div class="card-body">
                <form action="{{ route('vendors.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" class="form-control" name="name" value="{{old('name')}}" laceholder="Enter Vendor Name"
                                       required />
                            </div>
                            <div class="form-group">
                                <label for="">Telephone</label>
                                <input type="text" class="form-control" name="telephone" value="{{old('telephone')}}" placeholder="Enter Telephone Number" required/>
                            </div>
                            <div class="form-group">
                                <label for="">Mailing Address</label>
                                <input type="name" class="form-control" name="mailing_address" value="{{old('mailing_address')}}" id="" aria-describedby="emailHelp"
                                       placeholder="Enter Mailing Address" required />
                            </div>
                            <div class="form-group">
                                <label for="">Website</label>
                                <input type="name" class="form-control" name="website" value="{{old('website')}}" id="" aria-describedby="emailHelp"
                                       placeholder="Enter Website" required/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="">Contact Title</label>
                                <input type="text" class="form-control" name="contact_title" value="{{old('contact_title')}}" id="" placeholder="Enter Contact Title" required />
                            </div>
                            <div class="form-group">
                                <label for="">Contact Number</label>
                                <input type="text" class="form-control" name="contact_number" value="{{old('contact_number')}}" id="" placeholder="Enter Contact Number" required />
                            </div>
                            <div class="form-group">
                                <label for="">Contact Email</label>
                                <input type="text" class="form-control" name="contact_email" value="{{old('contact_email')}}" placeholder="Enter Contact Email" required />
                            </div>

                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea type="text" class="form-control" name="description" value="{{old('description')}}" placeholder="Enter Description"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success float-right shadow rounded font-weight-bold">Add New</button>
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
@endsection
