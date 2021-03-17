@extends('adminlte::page')

@section('title','Reconciliation List')

@section('content') 

<div class="container">
    <div class="card shadow-lg rounded">
        <div class="card-header">
            <div class="card-title">Reconciliation

            </div>
            <a href="{{route('reconciliation.create')}}" class="btn btn-success float-right shadow rounded">Add New</a>

        </div>
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
                                <a href="{{route('conciliation',$product->id)}}">reconcile</a>
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

</div>
@endsection
=======