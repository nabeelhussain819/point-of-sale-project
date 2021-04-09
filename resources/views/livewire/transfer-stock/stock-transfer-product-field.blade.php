<div>
    <div class="form-group">
        <label for="">Request #</label>
        <input type="text" class="form-control" name="request_id"

               placeholder="Request #" value="{{empty($transfer)?old('request_id'):$transfer->request_id}} "/>
    </div>

    <div class="form-group">
        <label for="">Date</label>

        <input type="date" class="form-control" name="transfer_date"
               value="{{empty($transfer)?old('transfer_date'):$transfer->transfer_date->format("Y-m-d")}}"
               required
        />
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="">Store Out</label>

                <select   wire:model="storeOutId" wire:change="storeOutSelect" name="store_out_id" id="" class="form-control" required>
                    <option value="">Please Select Store Out</option>
                    @foreach($stores as $item)
                        <option value="{{$item->id}}" {{(!empty($transfer)&& $transfer->store_out_id) == $item->id ? 'selected' : '' }}>{{$item->name}}</option>
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
                        <option value="{{$item->id}}" {{(!empty($transfer)&&$transfer->store_in_id == $item->id) ? 'selected' : '' }} >{{$item->name}}</option>
                    @endforeach
                </select>
            </div>

        </div>

    </div>


    <table class="table" id="products_table">
        <thead>
        <tr>
            <th scope="col">Quantity</th>
            <th scope="col-xs-4">Products</th>
        </tr>
        </thead>
        <tbody>
        @foreach($formFields as $key=>$value)
            <tr>
                <td>
                    <div class="input-group spinner">
                        <div class="row">
                            <input type="hidden"
                                   name="products[{{$key}}][id]"
                                   value="{{!empty($value['id']) ? $value['id']:null}}"/>

                            <input type="number" class="form-control"
                                   value="{{!empty($value['quantity']) ? $value['quantity']:null}}"
                                   name="products[{{$key}}][quantity]" required/>
                        </div>
                    </div>
                </td>

                <td>
                    <div class="input-group spinner">
                        <div class="row">
                            <select class="form-control" name="products[{{$key}}][product_id]">
                                <option>Please Select Product</option>
                                @foreach($products as $item)
                                    <option
                                            {{(!empty( $value['product_id']) ?? $item->product->id == $value['product_id']? 'selected':'')}}
                                            value="{{$item->product->id}}">{{$item->product->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </td>


                <td>

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-12">

            @if(!empty($this->products))
            <button id="add_row" wire:click.prevent="addRow({{$row}})"
                    class="btn btn-primary float-left shadow-lg">
                <i class="fa fa-plus"></i>
            </button>
                <button id='delete_row' wire:click.prevent="deleteRow({{$row}})"
                        class="float-right btn btn-danger shadow-lg"><i class="fa fa-trash"></i></button>
            @else
                <div class="alert alert-info">
                    Please select store to adding products or selected store does not have products
                </div>
            @endif


        </div>
    </div>
</div>
