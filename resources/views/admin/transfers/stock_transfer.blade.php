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
        $(document).on("click", "#markSerial", function () {

            const key = $("#scanSerial").val();
            const checkbBoxSelector =$(`.form-check .form-check-input[value=${key}]`);
            checkbBoxSelector.prop('checked', true);
            const product_id=   checkbBoxSelector.attr("data-product-id");
            appendSerialNumber(product_id,key)

        });

        function appendSerialNumber(product_id,key){

            $("#scanSerial").val("");
            let html = `    <input type="hidden"
                       value="${key}"
                       name="postSerial[${product_id}][]"
                >`

            $('.serial_container_input').append(html);
        }
    </script>
@endsection