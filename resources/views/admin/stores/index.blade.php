@extends('adminlte::page')

@section('title','All Stores')

@section('content')
    <div class="container">
        <h1>Store</h1>
        @if(auth()->user()->hasPermissionTo('store-edit'))

        @endif
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
                    <th scope="col">Location</th>
                    <th scope="col">Description</th>
                    <th scope="col">Status</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $count = 1;
                @endphp
                @forelse(\App\Models\Store::all() as $item)
                    <tr>
                        <td>{{$count++}}</td>
                        <td><a href="{{route('stores.edit',$item->id)}}">{{$item->name}}</a></td>
                        <td>{{$item->location}}</td>
                        <td>{{$item->description}}</td>
                        <td>
                            <span class="{!! $item->active == 0 ? 'badge badge-danger' : 'badge badge-success' !!}">{!!$item->active == 0 ? 'IN-ACTIVE' : 'ACTIVE'  !!}</span>
                        </td>

                        <td>
                            <div style="display: flex;margin:4px">
                                @if(auth()->user()->hasPermissionTo('store-edit'))
                                    <a class="btn btn-info" href="{{route('stores.edit',$item->id)}}"><i
                                                class="fa fa-pen"></i></a>
                                @endif
                                @if(auth()->user()->hasPermissionTo('store-delete'))

                                    <form action="{{route('stores.destroy',$item->id)}}" method="POST">
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
                    <p>No Stores</p>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection
