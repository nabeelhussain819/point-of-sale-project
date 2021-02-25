@extends('adminlte::page')

@section('title','Edit Vendor')
@section('content')
    <div class="container">
        <h4>Edit {{$vendor->name}}</h4>
        <div class="card">
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('vendors.update',$vendor->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="">Name</label>
                                <input type="text" class="form-control" name="name" value="{{$vendor->name}}"
                                       placeholder="Enter Vendor Name"
                                       required/>
                            </div>
                            <div class="form-group">
                                <label for="">Telephone</label>
                                <input type="text" class="form-control" name="telephone" value="{{$vendor->telephone}}"
                                       placeholder="Enter Telephone Number" required/>
                            </div>
                            <div class="form-group">
                                <label for="">Mailing Address</label>
                                <input type="text" class="form-control" name="mailing_address"
                                       value="{{$vendor->mailing_address}}" id="" aria-describedby="emailHelp"
                                       placeholder="Enter Mailing Address" required/>
                            </div>
                            <div class="form-group">
                                <label for="">Website</label>
                                <input type="text" class="form-control" name="website" value="{{$vendor->website}}"
                                       id="" aria-describedby="emailHelp"
                                       placeholder="Enter Website" required/>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="">Contact Title</label>
                                <input type="text" class="form-control" name="contact_title"
                                       value="{{$vendor->contact_title}}" id="" placeholder="Enter Contact Number"
                                       required/>
                            </div>
                            <div class="form-group">
                                <label for="">Contact Number</label>
                                <input type="text" class="form-control" name="contact_number"
                                       value="{{$vendor->contact_number}}" id="" placeholder="Enter Contact Number"
                                       required/>
                            </div>
                            <div class="form-group">
                                <label for="">Contact Email</label>
                                <input type="text" class="form-control" name="contact_email"
                                       value="{{$vendor->contact_email}}" placeholder="Enter Contact Email" required/>
                            </div>

                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea type="text" class="form-control" name="description"
                                          placeholder="Enter Description">{{$vendor->description}}</textarea>
                            </div>
                            <button type="submit" class="btn btn-success shadow rounded font-weight-bold float-right">
                                Submit
                            </button>
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
