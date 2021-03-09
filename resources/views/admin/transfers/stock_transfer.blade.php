@extends('adminlte::page')

@section('title','Stock Transfer')

@section('content')

    <div class="container">
        @if (isset($errors) && count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif
        <div class="card shadow rounded">
            <div class="card-body">
                <div class="panel panel-default">
                    <div class="panel-heading"><b>Stock Transfer</b>
                        <a class="btn btn-success float-right rounded shadow-lg" href="{{route('transfer.index')}}">See
                            Transfers</a>
                    </div>
                    <form action="{{route('transfer.store')}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">Request #</label>
                            <input type="text" class="form-control" name="request_id"
                                   placeholder="Request #" value="{{old('request_id')}}"/>
                        </div>

                        <div class="form-group">
                            <label for="">Date</label>
                            <input type="date" class="form-control" name="transfer_date" value="{{old('transfer_date')}}"
                            required
                            />
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="">Store Out</label>
                                    <select name="store_out_id" id="" class="form-control" required>
                                        <option value="">Please Select Store Out</option>
                                        @foreach($stores as $item)
                                            <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <p class="mt-3 text-center" style="font-size: 2.5rem">========></p>
                            <div class="col">
                                <div class="form-group">
                                    <label>Store In</label>
                                    <select name="store_in_id" id="" class="form-control" required>
                                        <option value="">Please Select Store In</option>
                                        @foreach($stores as $item)
                                            <option value="{{$item->id}}" selected>{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>

                        {{--<div class="form-group">--}}
                        {{--<label>Reference</label>--}}
                        {{--<input type="text" class="form-control" name="reference"--}}
                        {{--placeholder="Reference" required/>--}}
                        {{--</div>--}}
                        <input type="hidden" name="type_id" value="1"/>
                        @livewire('stock-transfer-product-field', ['products'=>$products])
                        <br>
                        <div>
                            <button class="btn btn-success font-weight-bold shadow rounded float-right" type="submit">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <script>
        $(document).ready(function () {
            let row_number = 1;
            $("#add_row").click(function (e) {
                e.preventDefault();
                let new_row_number = row_number - 1;
                $('#product' + row_number).html($('#product' + new_row_number).html()).find('td:first-child');
                $('#products_table').append('<tr id="product' + (row_number + 1) + '"></tr>');
                row_number++;
            });

            $("#delete_row").click(function (e) {
                e.preventDefault();
                if (row_number > 1) {
                    $("#product" + (row_number - 1)).html('');
                    row_number--;
                }
            });
        });

        function calculateColumn(index) {
            // var total = 0;
            // $('table tr').each(function () {
            //     var value = parseInt($('td ', this).eq(index).text());
            //     if (!isNaN(value)) {
            //         total += value;
            //     }
            // });
            // $('table tfoot td').eq(index).text('Total Sum: ' + total);
        }
    </script>
@endsection
