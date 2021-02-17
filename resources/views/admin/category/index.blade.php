@extends('adminlte::page')

@section('title','All Categories')

@section('content')
    <div class="container">
        <h1>Categories</h1>
        @if(auth()->user()->hasPermissionTo('category-create'))
            <a class="btn btn-success" href="{{route('categories.create')}}">Add New Category</a>
        @endif
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
                <th scope="col">Description</th>
                <th scope="col">Reference</th>
                <th scope="col">Active</th>
                <th scope="col">Created At</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @php
                $count = 1;
            @endphp
            @forelse($categories as $item)
                <tr>
                    <td>{{$count++}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->full_name ?? 'No description'}}</td>
                    <td>{{$item->reference ?? 'No reference given'}}</td>
                    <td>
                        <span class="{!! $item->active == 0 ? 'badge badge-danger' : 'badge badge-success' !!}">{!!$item->active == 0 ? 'IN-ACTIVE' : 'ACTIVE'  !!}</span>
                    </td>
                    <td>{{$item->created_at}}</td>
                    <td>
                        <div style="display: flex">
                            @if(auth()->user()->hasPermissionTo('category-edit'))

                                <a class="btn btn-info" href="{{route('categories.edit',$item->id)}}"><i
                                            class="fa fa-pen"></i></a>
                            @endif
                            @if(auth()->user()->hasPermissionTo('category-delete'))

                                <form action="{{route('categories.destroy',$item->id)}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <p>No Category</p>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
