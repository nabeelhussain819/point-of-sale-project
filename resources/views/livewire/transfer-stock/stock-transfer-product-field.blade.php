<div>
    <div class="form-group">
        <label for="">Request #</label>
        <input type="text" class="form-control" name="request_id"

               placeholder="Request #" value="{{empty($transfer)?old('request_id'):$transfer->request_id}} "/>
    </div>

    <div class="form-group">
        <label for="">Date</label>

        <input type="date" class="form-control" name="transfer_date"
               value="{{empty($transfer)?\Carbon\Carbon::now()->format('Y-m-d'):$transfer->transfer_date->format("Y-m-d")}}"
               required
        />
    </div>
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="">Store Out</label>

                <select wire:model="storeOutId" wire:change="storeOutSelect" name="store_out_id" id=""
                        class="form-control" required>
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
                        @if($storeOutId !=$item->id)
                            <option value="{{$item->id}}" {{(!empty($transfer)&&$transfer->store_in_id == $item->id) ? 'selected' : '' }} >{{$item->name}}</option>
                        @endif
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

            <tr wire:key="{{ $key }}">
                <td>
                    <div class="input-group spinner">
                        <div class="row">
                            <input type="hidden"
                                   name="products[{{$key}}][id]"
                                   value="{{!empty($value['id']) ? $value['id']:null}}"/>

                            <input type="number" class="form-control"
                                   {{$isCreated ?'disabled':''}}
                                   wire:change="validateProduct({{$key}})"
                                   wire:model="formFields.{{$key}}.quantity"
                                   value="{{!empty($value['quantity']) ? $value['quantity']:null}}"
                                   name="products[{{$key}}][quantity]" required/>
                            @if($formFields[$key]['error']) <span
                                    class="error">{{ $formFields[$key]['message'] }}</span> @endif
                        </div>
                    </div>
                </td>

                <td>

                    <div class="input-group spinner">
                        <div class="row">
                            <div class="input-group mb-3">
                                @if(isset($formFields[$key]['hasSerial'])&& $formFields[$key]['hasSerial']  && !$isCreated)
                                    <div class="input-group-append">
                                        <button
                                                wire:click.prevent="associateSerial({{$key}})"
                                                data-target="#serialNumber"
                                                data-toggle="modal"
                                                class="btn btn-primary"
                                                type="button">Serial numbers
                                        </button>
                                    </div>
                                @endIf
                                @if(isset($formFields[$key]['hasSerial'])&& $isCreated)
                                    <div class="input-group-append">
                                        <button
                                                wire:click.prevent="showSerials({{$key}})"
                                                data-target="#showSerialNumber"
                                                data-toggle="modal"
                                                class="btn btn-primary"
                                                type="button">Show Serial numbers
                                        </button>
                                    </div>
                                @endIf
                                <select class="form-control"
                                        {{$isCreated ?'disabled':''}}
                                        wire:change.debounce.1000ms="validateProduct({{$key}})"
                                        wire:model="formFields.{{$key}}.product_id"
                                        name="products[{{$key}}][product_id]">
                                    <option>Please Select Product</option>
                                    @foreach($products as $item)
                                        <option
                                                {{--{{(!empty( $value['product_id']) ?? $item->product->id == $value['product_id']? 'selected':'')}}--}}
                                                value="{{$item->product->id}}">{{$item->product->name}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </td>


                <td>
                    @if(!$isCreated)
                        <button type="button" class=" btn btn-danger shadow-lg" wire:click="removeRow({{$key}})">remove
                        </button>
                    @endif
                </td>
            </tr>

        @endforeach
        </tbody>
    </table>
    <div class="row">
        <div class="col-md-12">

            @if(!empty($this->products))
                <button type="button" id="add_row" wire:click.prevent="addRow({{$row}})"
                        class="btn btn-primary float-left shadow-lg">
                    <i class="fa fa-plus"></i>
                </button>

            @else
                <div class="alert alert-info">
                    Please select store to adding products or selected store does not have products
                </div>
            @endif


        </div>
    </div>
    <div class="text-right">
        @if(!$isCreated)
            <button {{$shouldSubmit ?'': 'disabled'}} class="btn btn-success font-weight-bold shadow rounded"
                    type="submit">
                Save
            </button>
        @endif
        @if(!$shouldSubmit)
            <span class="error d-block">Please remove all the error</span>
        @endif
    </div>


    <div class="serial_container_input">

    </div>
    {{--====Serial number===--}}
    {{--    @foreach($postSerials as $productId =>$serials)--}}
    {{--        @foreach($serials as $serial)--}}
    {{--            <input type="hidden"--}}
    {{--                   value="{{$serial}}"--}}
    {{--                   name="postSerial[{{$productId}}][]"--}}
    {{--            >--}}
    {{--        @endforeach--}}
    {{--    @endforeach--}}
    {{--====Serial number===--}}
    {{--MOdal --}}


    <div class="modal fade" wire:ignore.self id="serialNumber" tabindex="-1" role="dialog"
         aria-labelledby="serialNumber" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Product Serials Number</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body">


                    <div class="input-group mb-3">

                        <input id="scanSerial" placeholder="serial number" class="form-control"
                               aria-label="scanValue aria-describedby=" basic-addon2"/>

                        <div class="input-group-append">
                            <button id="markSerial" class="btn btn-outline-secondary " type="button">Add</button>
                        </div>

                    </div>

                    <hr/>

                    @if($showModal)

                        @foreach($productSerials as  $key=>$serial)

                            <div class="form-check">
                                <input class="form-check-input"
                                       wire:click="handleSerial({{$serial}},{{$key}})"
                                       type="checkbox"
                                       data-serial="{{$serial->serial_no}}"
                                       value="{{$serial->serial_no}}"
                                       data-product-id="{{$serial->product_id}}"
                                       id="serial-{{$serial->serial_no}}">
                                <label class="form-check-label" for="flexCheckDefault">
                                    {{$serial->serial_no}} {{$serial->checked}}
                                </label>
                            </div>
                        @endforeach
                        {{--@livewire('transfer.serial-number-livewire',['params'=>$serialFetchParams])--}}
                    @endif
                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" type="button" class="btn btn-primary">Done</button>
                </div>
            </div>
        </div>
    </div>
    {{--Modal--}}

    {{--MOdal show --}}


    <div class="modal fade" wire:ignore.self id="showSerialNumber" tabindex="-1" role="dialog"
         aria-labelledby="showSerialNumber" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Serial Numbers</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body">
                    @if($showModal)
                        @foreach($savedSerials as  $key=>$serial)
                            <div class="form-check">
                                <label class="form-check-label" for="flexCheckDefault">
                                    {{$serial->serial_no}}
                                </label>

                            </div>
                        @endforeach
                        {{--@livewire('transfer.serial-number-livewire',['params'=>$serialFetchParams])--}}
                    @endif
                </div>

                <div class="modal-footer">
                    <button data-dismiss="modal" type="button" class="btn btn-primary">Done</button>
                </div>
            </div>
        </div>
    </div>
    {{--Modal--}}
</div>

