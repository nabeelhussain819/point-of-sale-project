<div>
    <table class="table" id="products_table">
        <thead>
        <tr>
            <th class="col-xs-4">Lookup</th>
            <th class="col-xs-4">Product</th>
            <th class="col-xs-4">Quantity</th>
            <th class="col-xs-4">Price</th>
            <th class="col-xs-4">Total Price</th>
        </tr>
        </thead>
        <tbody>

        @foreach($inputs as $key => $value)
            <tr id="product0">
                <td>
                    <input type="text" class="form-control" name="products[{{$key}}][lookup]"
                           placeholder="Enter Lookup"/>
                </td>
                <td>
                    <div class="input-group spinner">
                        <div class="row">
                            {{--@todo optimize quqery do not repeat --}}
                            <select class="form-control" name="products[{{$key}}][product_id]">
                                <option value="">Please Select Product</option>
                                @foreach(\App\Models\Product::all() as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </td>
                <td><input type="text" class="form-control" name="products[{{$key}}][quantity]"
                           placeholder="Enter Quantity"/>
                </td>
                <td><input type="text" class="form-control" name="products[{{$key}}][price]"
                           placeholder="Enter Quantity"/>
                </td>
            </tr>
        @endforeach
    </table>
    <div class="row">
        <div class="col-md-12">
            <button class="btn text-white btn-info btn-sm" wire:click.prevent="add({{$i}})">Add</button>
            <button id='delete_row' class="float-right btn btn-danger shadow-lg">- Delete Row</button>
        </div>
    </div>
</div>