@extends('adminlte::page')

@section('title','Product Serial Number Association')

@section('content')

    <div class="container">
        <div class="card shadow rounded">
            <div class="card-body">
                <form action="{{route('transfer.associate-product-serial',$transfer->id)}}" method="POST">
                    <table class="table">
                        @foreach($pOProducts as  $key=>$pOProduct)
                            @for($index=0; $index < $pOProduct->quantity ;$index++)
                                <tr>
                                    <td>{{$pOProduct->product->name}}</td>
                                    <td><input required class="form-control" placeholder="Insert Product Serial number"
                                               name="serialProducts[{{$pOProduct->product->id}}][{{$index}}]"></td>
                                </tr>
                            @endfor
                        @endforeach
                    </table>
                    @csrf
                    <button class="btn btn-success" type="submit">Submit</button>
                </form>
            </div>
        </div>

    </div>

@endsection
