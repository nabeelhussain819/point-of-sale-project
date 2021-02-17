@extends('adminlte::page')

@section('title','All Roles')

@section('content')
    <div class="container">
        <h1>Customers</h1>
        <a class="btn btn-success" href="{{route('customers.create')}}">Add New Customers</a>
        <br>
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Email</th>
                <th scope="col">Phone</th>
                <th scope="col">Telephone</th>
                <th scope="col">Products</th>
                <th scope="col">Created At</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @php
                $count = 1;
            @endphp
            @forelse($customers as $item)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$item->customer->name}}</td>
                    <td>{{$item->customer->email}}</td>
                    <td>{{$item->customer->phone}}</td>
                    <td>{{$item->customer->telephone}}</td>
                    <td>{{$item->product->name}}</td>
                    <td>{{$item->created_at}}</td>
                    <td>
                        <div style="display: flex">
                            <a class="btn btn-info" href="{{route('customers.edit',$item->customer->id)}}"><i class="fa fa-pen"></i></a>
                            <form action="{{route('customers.destroy',$item->customer->id)}}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <a href="{{route('customers.create')}}">No Customers, Please Create One</a>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
