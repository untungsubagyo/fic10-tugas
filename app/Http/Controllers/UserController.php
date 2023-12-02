<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // $users = \App\Models\User::paginate(10);
        $users = DB::table('users')
            ->when($request->input('name'), function ($query, $name) {
                return $query->where('name', 'like', '%' . $name . '%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('pages.users.index', compact('users'), ['type_menu' => 'users']);
    }

    public function create()
    {
        return view('pages.users.create', ['type_menu' => 'users']);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password']=Hash::make($request->password);
        \App\Models\User::create($data);
        return redirect()->route('users.index')->with('success','User successfully created');
    }

    public function edit(User $user){
        return view('pages.users.edit',compact('user'), ['type_menu' => 'users']);
    }

    public function update(UpdateUserRequest $request,User $user)
    {
        $data = $request->validated();
        $user->update($data);
        return redirect()->route('users.index')->with('success','User successfully updated');
    }

    public function destroy(User $user){
        $user->delete();
        return redirect()->route('users.index')->with('success','User successfully deleted');
    }
}
