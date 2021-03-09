<div>
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
                                <option value="">Please Select Product</option>
                                @foreach($products as $item)

                                    <option
                                            {{(!empty( $value['product_id']) ?? $item->id == $value['product_id']? 'selected':'')}}
                                            {{--selected="{{!empty($value['product_id']) ? ($item->id == $value['product_id']? true:false):null}}"--}}
                                            value="{{$item->id}}">{{$item->name}}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-12">
            <button id="add_row" wire:click.prevent="addRow({{$row}})"
                    class="btn btn-primary float-left shadow-lg">
                <i class="fa fa-plus"></i>
            </button>
            <button id='delete_row' wire:click.prevent="deleteRow({{$row}})" class="float-right btn btn-danger shadow-lg"><i class="fa fa-trash"></i></button>
        </div>
    </div>
</div>
