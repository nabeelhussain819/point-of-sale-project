<div>
  <form wire:submit.prevent="submit">
    @if (session('success'))
      <div class="alert alert-success" role="alert">
        {{ session('success') }}
      </div>
    @endif
    <div class="form-group">
      <label for="exampleInputEmail1" class="font-weight-normal">Name</label>
      <select wire:change="storeProducts" wire:model="store_id" wire:model="selectedStore" class="form-control"
              name="store_id">
        <option value="">Please Select Store</option>

        @foreach($stores as $store)
          <option value="{{$store->id}}">{{$store->name}}</option>
        @endforeach
      </select>
      @error('store_id') <span class="error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
      <label for="exampleInputEmail1" class="font-weight-normal">Title</label>
      <input type="text" class="form-control" wire:model="name" id="exampleInputEmail1" aria-describedby="emailHelp"
             placeholder="Name" required>
      @error('name') <span class="error">{{ $message }}</span> @enderror
    </div>
    <table class="table" id="products_table">
      <thead>
      <tr>
        <th class="col-xs-4">Lookup</th>
        <th class="col-xs-4">Product</th>
        <th class="col-xs-4">Physical Quantity</th>

      </tr>
      </thead>
      <tbody>

      @foreach($inputs as $key => $value)

        <tr id="product0">
          <td>
            <input type="text" class="form-control"
                   placeholder="Enter Lookup"/>
          </td>
          <td>
            <div class="input-group spinner">
              <div class="row">
                <select class="form-control"
                        name="products[{{$key}}][product_id]"
                        wire:model="products.{{$key}}.product_id"
                >
                  <option value="">Please Select Product</option>
                  @foreach($storeProducts as $item)
                    <option value="{{$item['id']}}">{{$item['name']}}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </td>
          <td><input type="text"
                     class="form-control"
                     wire:model="products.{{$key}}.physical_quantity"
                     name="products[{{$key}}][physical_quantity]"
                     placeholder="Enter Quantity"/>
          </td>

        </tr>
      @endforeach
    </table>
    <div class="row">
      <div class="col-md-12">
        <button class="btn text-white btn-info " wire:click.prevent="add({{$i}})"><i class="fa fa-plus"></i></button>
        <button id='delete_row' class="float-right btn btn-danger shadow-lg"><i class="fa fa-trash"></i></button>
      </div>
    </div>


    <button type="submit" wire:click="store()" class="btn btn-success font-weight-bold shadow-lg rounded float-right">Add New</button>
  </form>
</div>
