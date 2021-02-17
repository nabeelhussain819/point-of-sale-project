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
        @if(auth()->user()->hasPermissionTo('inventory-create'))
            <h1>All Inventories</h1> <a class="btn btn-success" href="{{ route('inventory.create') }}">Add
                Inventories</a>
        @endif
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
                            @if(auth()->user()->hasPermissionTo('inventory-edit'))

                                <a class="btn btn-info"
                                   href="{{ route('inventory.edit',$item->id) }}"><i
                                            class="fa fa-pen"></i></a>
                            @endif
                            @if(auth()->user()->hasPermissionTo('inventory-delete'))

                                <form action="{{ route('inventory.destroy',$item->id) }}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <p>No Inventories</p>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
