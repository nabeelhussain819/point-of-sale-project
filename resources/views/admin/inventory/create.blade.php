@extends('adminlte::page')

@section('title','Create Inventory')

@section('content')
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"><h4>New Inventory</h4>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('inventory.store')}}" method="POST">

                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                @csrf
                                <div class="form-group">
                                    <label>Inventory Name</label>
                                    <input type="text" name="name" class="form-control" value="{{old('name')}}" placeholder="Name"/>
                                </div>
                                <div class="form-group">
                                    <label>Lookup</label>
                                    <input type="number" name="lookup" class="form-control" placeholder="Lookup" value="{{old('lookup')}}"/>
                                </div>

                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input type="text" name="quantity" step="0" min="0" value="{{old('quantity')}}" class="form-control"
                                           placeholder="Quantity"/>
                                </div>
                                <div class="form-group">
                                    <label>Cost</label>
                                    <input type="number" name="cost" value="{{old('cost')}}" class="form-control" placeholder="Cost"/>
                                </div>
                                <div class="form-group">
                                    <label>Bin</label>
                                    <input type="text" name="bin" value="{{old('bin')}}" class="form-control"
                                           placeholder="Bin..."/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Vendor</label>
                                    <select name="vendor_id" class="form-control">
                                        @foreach(\App\Models\Vendor::all() as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Product</label>
                                    <select name="product_id" class="form-control">
                                        @foreach(\App\Models\Product::all() as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>UPC</label>
                                    <input type="number" name="UPC" {{old('UPC')}} class="form-control" placeholder="UPC"/>
                                </div>
                                <div class="form-group">
                                    <label>Extended Cost</label>
                                    <input type="number" name="extended_cost" class="form-control"
                                           placeholder="Lookup" {{old('lookup')}}/>
                                </div>

                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" class="form-control"
                                    >{{old('description')}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success font-weight-bold">Add New</button>

                </form>

            </div>
        </div>
    </div>
    </div>

    @if (isset($errors) && count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <br>
    @if(session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
        @endif
        </div>
        <div class="container">
            @if(auth()->user()->hasPermissionTo('inventory-create'))
                <h4>All Inventories
                    {{--<button class="btn btn-success float-right shadow-rounded font-weight-bold" data-toggle="modal"--}}
                            {{--data-target="#exampleModalCenter">Add New--}}
                    {{--</button>--}}
                </h4>
            @endif
            <br>
            <div class="card shadow rounded">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Lookup</th>

                                <th scope="col">Product Name</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Cost</th>
                                <th scope="col">Extended Cost</th>
                                <th scope="col">Bin</th>
                                <th scope="col">Store Name</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $count = 1;
                            @endphp
                            @forelse($inventories as $item)
                                <tr>
                                    <td>{{ $count++ }}</td>
                                    <td>{{$item->lookup}}</td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{$item->cost}}</td>
                                    <td>{{$item->extended_cost}}</td>
                                    <td>{{$item->bin}}</td>
                                    <td>{{ $item->store->name }}</td>
                                    <td>
                                        <div style="display: flex;">
                                            @if(auth()->user()->hasPermissionTo('inventory-edit'))

                                                <a class="btn btn-info mr-1 rounded"
                                                   href="{{ route('inventory.edit',$item->id) }}"><i
                                                            class="fa fa-pen"></i></a>
                                            @endif
                                            @if(auth()->user()->hasPermissionTo('inventory-delete'))

                                                <form action="{{ route('inventory.destroy',$item->id) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger ml-1"><i
                                                                class="fa fa-trash"></i></button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <p>No Inventories</p>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection
