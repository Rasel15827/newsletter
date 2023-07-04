<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        $users = User::whereHas('roles', function ($query) {
            $query->where('slug', 'user');
        })->get();

        return view('admin.users', compact(['users']));
    }

    // create user
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Create User
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => 'active',
            'password' => Hash::make($request->password),
            'plain_password' => encrypt($request->password),
        ]);

        $user->roles()->attach(Role::where('slug', 'user')->first());
        echo json_encode(array('message' => 'users successfully created'));
    }
    // delete user
    public function show(Request $request)
    {
        $user = User::find($request->id);
        echo json_encode(array("user_name"=> $user->first_name." ".$user->last_name,"user_email"=>$user->email,"user_password"=>decrypt($user->plain_password)));
    }

    // delete user
    public function destroy(Request $request)
    {
        $user = User::find($request->id);

        if($user){
            $user->delete();
        }
        echo json_encode(array("message"=>"user successfully deleted"));
    }
}
