@extends('adminlte::page')
@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop
@section('content')
{{--  sir this a jugar i know  --}}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{ __('You are logged in!') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Store Assigned</th>
                    <th scope="col">Store Description</th>
                    <th scope="col">Assigned As</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $count = 1;
                @endphp
                @forelse(\App\Models\UserStore::with('store','user','role')->where('user_id',auth()->id())->get() as $item)
                    <tr>
                        <td>{{$count++}}</td>
                        <td>{{$item->user->name}}</td>
                        <td>{{$item->user->email}}</td>
                        <td>{{$item->store->name}}</td>
                        <td>{{$item->store->description}}</td>
                        <td>{{$item->role->name}}</td>
                        <td>
                            {{--                        <div style="display: flex">--}}
                            {{--                            <a class="btn btn-info" href="{{route('users.edit',$item->user->id)}}"><i class="fa fa-pen"></i></a>--}}
                            {{--                            <form action="{{route('users.destroy',$item->user->id)}}" method="POST">--}}
                            {{--                                @method('DELETE')--}}
                            {{--                                @csrf--}}
                            {{--                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>--}}
                            {{--                            </form>--}}
                            {{--                        </div>--}}
                        </td>
                    </tr>
                @empty
                    <a href="{{route('stores.create')}}">No Stores, Please Create One</a>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
