<div class="container">
    <h4>New Device Type</h4>
    <div class="card shadow rounded">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Product Name</label>
                        <select wire:model="product_id" class="form-control" name="product_id" required>
                            <option >Please select </option>
                            @foreach($products  as $product)
                                <option value="{{$product->id}}">{{$product->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Device Name</label>
                        <select wire:model="device_type_id" class="form-control" name="device_type_id" required>
                            <option >Please select </option>
                            @foreach($devicesTypes  as $devicesType)
                                <option value="{{$devicesType->id}}">{{$devicesType->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Brand Name</label>
                        <select wire:model="brand_id" class="form-control" name="brand_id" required>
                            <option >Please select </option>
                            @foreach($brands  as $brand)
                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <br/>
                        <button wire:click="store()"
                                class="btn btn-success font-weight-bold shadow-lg rounded float-right">Add
                            New
                        </button>
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
    </div>
</div>