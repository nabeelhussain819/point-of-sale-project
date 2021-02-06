<select name="user_id" class="form-control">
    @foreach(\App\Models\Store::all() as $store)
        <option value="">Please Select Store</option>
        <option value="{{$store->id}}">{{$store->name}}</option>
    @endforeach
</select>
