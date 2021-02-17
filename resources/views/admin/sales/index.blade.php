@extends('adminlte::page')

@section('title','All Products')

@section('content')
    <div class="container">
        <h1>Sales</h1>
        <a class="btn btn-success" href="{{route('sales.create')}}">New Sale</a>
        <br>
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="table-responsive">

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Description</th>
                    <th scope="col">Tax</th>
                    <th scope="col" class="unitprice">Unit Price</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $count = 1;
                @endphp
                @forelse($sales as $item)
                    <tr>
                        <td>{{$count++}}</td>
                        <td>{{$item->product->name}}</td>
                        <td>{{$item->description}}</td>
                        <td>{{$item->tax}}</td>
                        <td>{{$item->product->cost}}</td>
                        <td>
                            <div style="display: flex;margin:4px">
                                <a class="btn btn-info" href="{{route('sales.edit',$item->id)}}"><i class="fa fa-pen"></i></a>
                                <form action="{{route('sales.destroy',$item->id)}}" method="POST">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <a href="{{route('sales.create')}}">No Sales yet</a>
                @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <td style="display: none;">Total:</td>
                        <td style="display: none;">Total:</td>
                        <td style="display: none;">Total:</td>
                        <td style="display: none;">Total:</td>
                        <td class="float-right"></td>

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            $("table thead th").each(function (i) {
                calculateColumn(i);
            });
        });
        function calculateColumn(index) {
            var total = 0;
            $('table tr').each(function () {
                var value = parseInt($('td ', this).eq(index).text());
                if (!isNaN(value)) {
                    total += value;
                }
            });
            $('table tfoot td').eq(index).text('Total Sum: ' + total);
        }
    </script>
@endsection
