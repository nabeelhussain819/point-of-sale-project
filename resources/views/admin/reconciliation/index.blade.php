@extends('adminlte::page')

@section('title','Reconciliation Create')

@section('content')
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
                        <a href="{{route('conciliation',$product->id)}}">reconcile</a>
                    </td>
                </tr>
            @endforeach
            </tbody>

            <tfoot>
            </tfoot>
        </table>
    </div>

@endsection
