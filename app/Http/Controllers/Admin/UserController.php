<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\SendEmailToUsers;
use Illuminate\Validation\Rules;
use App\Jobs\SendEmailToUsersJob;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
    // create user
    public function send_email(Request $request)
    {
        $request->validate([
            'subject' => 'required', 'string', 'max:255',
            'message' => 'required',
        ]);

        $users = User::whereHas('roles', function ($query) {
            $query->where('slug', 'user');
        })->get(); // Fetch all users from the database
        $subject = $request->subject;
        $message = $request->message;
    
        // foreach ($users as $user) {
            // Mail::to($user->email)->send(new SendEmailToUsers($subject, $message));
        // }

        
        foreach ($users as $user) {
            SendEmailToUsersJob::dispatch($user, $subject, $message);
        }
    
        echo json_encode(array('message' => 'Email successfully sent'));
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
