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

        @php
            $options='';
                foreach($productDropDown as $item){
             $options .="<option value='{$item->id}'>{$item->name}</option>";
                }
        @endphp
        @foreach($inputs as $key => $value)

            <tr id="product0">
                <td>
                    <input {{$isCreated ?'disabled': ''}}
                           wire:blur="lookUp({{$key}})"
                           wire:model="products.{{$key}}.lookup"
                           type="text"
                           class="form-control"
                           placeholder="Enter Lookup"/>
                </td>
                <td>
                    <div class="input-group spinner">
                        <div class="row">
                            {{--@todo optimize quqery do not repeat --}}
                            <select {{$isCreated ?'disabled': ''}} wire:change="setPrice({{$key}})" class="form-control"
                                    wire:model="products.{{$key}}.product_id"
                                    name="products[{{$key}}][product_id]">
                                <option value="">Please Select Product</option>
                                {{!!$options!!}}
                            </select>
                        </div>
                    </div>
                </td>
                <td><input type="number"
                           class="form-control"
                           wire:change="quantity({{$key}})"
                           wire:model.lazy="products.{{$key}}.quantity"
                           name="products[{{$key}}][quantity]"
                           placeholder="Enter Quantity"/>
                </td>

                <td>
                    <span>{{$products[$key]['price']}}</span>
                    <input type="hidden" class="form-control"
                           wire:model="products.{{$key}}.price"
                           name="products[{{$key}}][price]"
                           placeholder="Enter Price"/>
                </td>
                <td>
                    <span>{{$productPrices[$key]}}</span>

                </td>
            </tr>
        @endforeach
    </table>
    <div class="row">
        @if(!$isCreated)
        <div class="col-md-12">
            <button class="btn text-white btn-info "  wire:click.prevent="add({{$i}})"><i class="fa fa-plus"></i></button>
            <button id='delete_row' class="float-right btn btn-danger shadow-lg"><i class="fa fa-trash"></i></button>
        </div>
       @endif
    </div>
</div>