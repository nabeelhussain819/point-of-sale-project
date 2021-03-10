@extends('adminlte::page')

@section('title','Reconciliation Create')

@section('content')
    <div class="table-responsive">
        <h1>#{{$conciliation->id}} {{$conciliation->name}}</h1>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Product</th>
                {{--<th scope="col">description</th>--}}
                <th scope="col">physical quantity</th>
                <th scope="col">system quantity</th>
            </tr>
            </thead>
            <tbody>

            @foreach($inventories as $inventory)
                <tr>
                    <td>{{$inventory->id}}</td>
                    <td>{{$inventory->product->name}}</td>
                    <td>{{$concileData[$inventory->product_id]['physical_quantity']}}</td>
                    <td>{{$inventory->quantity}}</td>

                </tr>
            @endforeach
            </tbody>

            <tfoot>
            </tfoot>
        </table>
    </div>

@endsection
