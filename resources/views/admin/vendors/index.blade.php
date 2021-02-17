@extends('adminlte::page')

@section('title','All Vendors')

@section('content')
    <div class="container">
        <br>
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
    </div>
    <div class="container">
        <h1>All Vendors</h1>
        @if(auth()->user()->hasPermissionTo('vendor-create'))
        <a class="btn btn-success" href="{{ route('vendors.create') }}">Add New Vendor</a>
        @endif
        <br>
        <div class="table-responsive">

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Telephone</th>
                    <th scope="col">Mailing Address</th>
                    <th scope="col">website</th>
                    <th scope="col">Contact Title</th>
                    <th scope="col">Contact Number</th>
                    <th scope="col">Contact Email</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $count = 1;
                @endphp
                @forelse($vendors as $item)
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ $item->name}}</td>
                        <td>{{ $item->telephone }}</td>
                        <td>{{ $item->mailing_address }}</td>
                        <td>{{ $item->website }}</td>
                        <td> {{$item->contact_title ?? 'No Contact Title'}} </td>
                        <td>{{$item->contact_number ?? 'No Contact Number'}}</td>
                        <td>{{$item->contact_email ?? 'No Contact Email'}}</td>
                        <td>
                            <div style="display: flex">
                                @if(auth()->user()->hasPermissionTo('vendor-edit'))
                                    <a class="btn btn-info"
                                       href="{{ route('vendors.edit',$item->id) }}"><i
                                                class="fa fa-pen"></i></a>
                                @endif
                                @if(auth()->user()->hasPermissionTo('vendor-delete'))
                                    <form action="{{ route('vendors.destroy',$item->id) }}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <a href="{{ route('stores.create') }}">No Vendors, Please Create One</a>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
