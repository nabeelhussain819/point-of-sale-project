@extends('adminlte::page')

@section('title','StockBin')

@section('content')

    <div class="container">
        <div class="card shadow rounded">
            <div class="card-body">
                @if(session('success'))

                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
{{--                <div class="table-responsive">--}}
{{--                    <table class="table">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th scope="col">#</th>--}}
{{--                            <th scope="col">From</th>--}}
{{--                            <th scope="col">To</th>--}}
{{--                            <th scope="col">Transfer date</th>--}}

{{--                            <th scope="col">Actions</th>--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                        @php--}}
{{--                            $count = 1;--}}
{{--                        @endphp--}}
{{--                        @forelse($transfers as $item)--}}
{{--                            <tr>--}}
{{--                                <td>{{$item->id}}</td>--}}

{{--                                <td>{{$item->storeIn->name}}</td>--}}
{{--                                <td>{{$item->storeOut->name}}</td>--}}

{{--                                <td>{{$item->transfer_date}}</td>--}}
{{--                                --}}{{--Armash please check this--}}
{{--                                <td>--}}
{{--                                    <div style="display: flex;">--}}
{{--                                        <a class="btn btn-info" href="{{route('transfer.received',$item)}}">Received</a>--}}
{{--                                        <form action="{{route('transfer.delete',$item)}}" method="POST">--}}
{{--                                            @method('DELETE')--}}
{{--                                            @csrf--}}
{{--                                            <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i>--}}
{{--                                            </button>--}}
{{--                                        </form>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        @empty--}}
{{--                        @endforelse--}}
{{--                        </tbody>--}}
{{--                        <tfoot>--}}
{{--                        </tfoot>--}}
{{--                    </table>--}}
{{--                </div>--}}

                <transfer></transfer>
            </div>
        </div>

    </div>
@endsection
