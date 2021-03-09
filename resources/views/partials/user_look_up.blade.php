<select name="user_id" class="form-control">
    <option value="">Please Select User</option>

@foreach(\App\Models\User::all() as $user)
        <option value="{{$user->id}}">{{$user->name}} <span class="badge badge-primary">({{$user->getRoleNames()->first()}})</span></option>
    @endforeach
</select>
