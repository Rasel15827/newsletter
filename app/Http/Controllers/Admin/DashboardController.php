<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Data;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){

        $total_users = User::whereHas('roles', function ($query) {
            $query->where('slug', 'user');
        })->count();

        $total_entries = Data::count();

        return view('admin.dashboard',compact(['total_users','total_entries']));
    }
    public function user_index(){

        // $all_data  = Data::orderBy('id','DESC')->get();
        // return view('user.dashboard',compact(['all_data']));
        return view('user.dashboard');
    }
}
