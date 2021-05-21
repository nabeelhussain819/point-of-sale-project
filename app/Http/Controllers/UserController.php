<?php

namespace App\Http\Controllers;

use App\Helpers\ArrayHelper;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('admin.users.index',['user' => UserDetail::paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.users.create',['role' => Role::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = new User();
        $user->password = bcrypt('password');
        $user->assignRole($request->input('role_id'));
        $name = $request->input('first_name') . $request->input('last_name');
        $userData = ArrayHelper::merge($request->validated(), ['name' => $name]);
        $user->fill($userData)->save();
        $detail = new UserDetail();
        $detail->user()->associate($user);
        $detail->fill($request->validated())->save();
        return redirect('users')->with('success','User Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
        return view('admin.users.show',['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
        return view('admin.users.edit',['user' => $user,'role' => Role::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
        if ($request->input('assign')) {
            $this->assignRole($request);
        } elseif ($request->input('removeRole')) {
            $this->deassignRole($request);
        } else {
            $user->fill($request->all())->save();
            $user->assignRole($request->input('role_id'));
            $detail = UserDetail::where('user_id', $user->id)->first();
            $detail->user()->associate($user);
            $detail->fill($request->all())->save();
        }
        return redirect()->back()->with('success',"$user->name Updated");
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function destroy(User $user)
    {
        //
        $user->delete();
        return redirect('users')->with('success',"$user->name Deleted");
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignRole(Request $request)
    {
        $user = User::where('id',$request->input('user_id'))->first();
        $user->syncRoles($request->input('role_id'));
        return redirect()->back()->with('success','Role Assigned ');
    }

    public function deassignRole(Request $request)
    {
        $user = User::where('id',$request->input('user_id'))->first();
        $user->syncRoles($request->input('role'));
        return redirect()->back()->with('success','Role Removed');
    }
}