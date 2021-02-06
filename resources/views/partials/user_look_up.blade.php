<select name="user_id" class="form-control">
    <option value="">Please Select User</option>

@foreach(\App\Models\User::where('id','!=',auth()->id())->get() as $user)
        <option value="{{$user->id}}">{{$user->name}}</option>
    @endforeach
</select>
