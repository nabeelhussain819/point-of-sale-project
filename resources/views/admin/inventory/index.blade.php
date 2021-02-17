@extends('adminlte::page')

@section('title','All Inventories')

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
        <h1>All Inventories</h1> <a class="btn btn-success" href="{{ route('inventory.create') }}">Add Inventories</a>

        <br>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Quantity</th>
                <th scope="col">Product Name</th>
                <th scope="col">Vendor Name</th>
                <th scope="col">Phone</th>
            </tr>
            </thead>
            <tbody>
            @php
                $count = 1;
            @endphp
            @forelse($inventories as $item)
                <tr>
                    <td>{{ $count++ }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->vendor->name }}</td>
                    <td>
                        <div style="display: flex">
                            <a class="btn btn-info"
                               href="{{ route('inventory.edit',$item->id) }}"><i
                                        class="fa fa-pen"></i></a>
                            <form action="{{ route('inventory.destroy',$item->id) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <a href="{{ route('inventory.create') }}">No Inventories, Please Create One</a>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
