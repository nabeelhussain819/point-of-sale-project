<select name="role_id" class="form-control">
    <option value="">Please Select Role</option>
    @foreach(\Spatie\Permission\Models\Role::where('id','!=',1)->get() as $item)
        <option value="{{$item->id}}" {{$item->id}}>{{$item->name}}</option>
    @endforeach
</select>
