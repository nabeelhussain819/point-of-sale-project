@extends('adminlte::page')

@section('title','Reconciliation Create')

@section('content')

    <div class="card shadow rounded">
        <div class="card-body">
    <div class="table-responsive">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($reconciliations as $product)
                <tr>
                    <td>{{$product->id}}</td>
                    <td>{{$product->name}}</td>
                    <td>
                        <!-- <a onclick="return confirm('Are you sure you want to concile? It will lock all the inventory')" href="{{route('conciliation',$product->id)}}">Reconcile</a> -->
                        <a href="{{route('conciliation',$product->id)}}">Reconcile</a>
                    </td>
                </tr>
            @endforeach
            </tbody>

            <tfoot>
            </tfoot>
        </table>
    </div>
    </div>
    </div>

@endsection
