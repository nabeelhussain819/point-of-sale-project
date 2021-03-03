@extends('adminlte::page')

@section('title','StockBin')

@section('content')

    <div class="container">
        <div class="card shadow rounded">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Request</th>
                            <th scope="col">From</th>
                            <th scope="col">To</th>
                            <th scope="col">Products</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Date</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $count = 1;
                        @endphp
                        @forelse($transfers as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>{{$item->request}}</td>
                                <td>{{$item->storeIn->name}}</td>
                                <td>{{$item->storeOut->name}}</td>
                                <td>{{$item->inventory->product->name}}</td>
                                <td>{{$item->quantity}}</td>
                                <td>{{$item->date}}</td>
                                <td>
                                    <div style="display: flex;">
                                    <a class="btn btn-info" href="{{route('transfer.received',$item)}}"><i class="fa fa-pen"></i></a>
                                    <form action="{{route('transfer.delete',$item)}}" method="POST">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                    </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                        @endforelse
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection
