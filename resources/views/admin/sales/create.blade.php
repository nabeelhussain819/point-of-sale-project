@extends('adminlte::page')

@section('title','Create A New Sale')
@section('content')
    <div class="container bg-white ">
        <div class="">
            @if(empty($preloadProduct))
                <sales></sales>
            @else
                <sales :pre="{{$preloadProduct}}"></sales>
            @endif

        </div>

    </div>
@endsection