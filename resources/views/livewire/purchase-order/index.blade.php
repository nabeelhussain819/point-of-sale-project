<div>
    <form action="{{route('purchase-order.store')}}" method="POST">
        <div class="form-group">
            <label>Vendor </label>

            <select class="form-control" name="vendor_id">
                <option value="">Please Select Vendor</option>
                @foreach($vendors as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Store </label>
            <select class="form-control" name="store_id">
                <option value="">Please Select Store</option>

                @foreach(\App\Models\Store::all() as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="">Expected Date </label>
            <input type="date" class="form-control" value="{{\Carbon\Carbon::now()->format('Y-m-d')}}"
                   name="expected_date"/>
        </div>

        <br>
        <input type="hidden" name="type_id" value="2"/>
        @csrf

        <livewire:product-fields/>
        <br>
        <div>
            <button class="btn btn-success font-weight-bold shadow rounded float-right" type="submit">Save
            </button>
        </div>
    </form>
</div>
