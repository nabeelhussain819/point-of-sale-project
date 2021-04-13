@extends('adminlte::page')

@section('title','Repair Module')

@section('content')

    <div class="container bg-white">
        <refund :order="{{$order}}"></refund>
    </div>
@endsection
