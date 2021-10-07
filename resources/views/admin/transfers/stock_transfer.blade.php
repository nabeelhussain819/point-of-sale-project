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

                        <input type="hidden" name="type_id" value="1"/>
                        @livewire('stock-transfer-product-field', ['stores'=>$stores])
                        <br>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script>
        $(document).ready(function () {

        });

        $(document).on("click", "#markSerial", function () {

            const key = $("#scanSerial").val();
            $(`.form-check .form-check-input[value=${key}]`).prop('checked', true);
          //  $(`.form-check .form-check-input[value=${key}]`).trigger('click');
            // .prop('checked', true);
        })
    </script>
@endsection