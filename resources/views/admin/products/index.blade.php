@extends('adminlte::page')

@section('title','All Products')

@section('content')
    <div class="container">
        <h1>Products</h1>
        <a class="btn btn-success" href="{{route('products.create')}}">Add Product</a>
        <br>
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="table-responsive">

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">UPC</th>
                    <th scope="col">Cost</th>
                    <th scope="col">Category</th>
                    <th scope="col">Department</th>
                    <th scope="col">Active</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $count = 1;
                @endphp
                @forelse(\App\Models\Product::all() as $item)
                    <tr>
                        <td>{{$count++}}</td>
                        <td><a href="{{route('products.edit',$item->id)}}">{{$item->name}}</a></td>
                        <td>{{$item->description}}</td>
                        <td>{{$item->UPC}}</td>
                        <td>{{$item->cost}}</td>
                        <td>{{$item->category->name}}</td>
                        <td>{{$item->department->name}}</td>
                        <td><span class="{!! $item->active == 0 ? 'badge badge-danger' : 'badge badge-success' !!}">{!!$item->active == 0 ? 'IN-ACTIVE' : 'ACTIVE'  !!}</span></td>
                        <td>
                            <div style="display: flex;margin:4px">
                                <a class="btn btn-info" href="{{route('products.edit',$item->id)}}"><i class="fa fa-pen"></i></a>
                                <form action="{{route('products.destroy',$item->id)}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <a href="{{route('products.create')}}">No Products, Please Create One</a>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
