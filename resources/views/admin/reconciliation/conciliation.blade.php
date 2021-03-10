@extends('adminlte::page')

@section('title','Reconciliation Create')

@section('content')
    @livewire('conciliation',
    ['concileData'=>$concileData,
    'conciliation'=>$conciliation,
    'inventories'=>$inventories])
@endsection
