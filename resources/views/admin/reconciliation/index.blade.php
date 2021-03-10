@extends('adminlte::page')

@section('title','Reconciliation Create')

@section('content')
    {{------------------table---------------}}
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
                </tr>
            @endforeach
            </tbody>

            <tfoot>
            </tfoot>
        </table>
    </div>
    {{------------------table---------------}}

    <livewire:reconciliation/>
@endsection
