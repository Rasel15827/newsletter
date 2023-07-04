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

        return view('admin.dashboard',compact(['total_users']));
    }
    public function user_index(){

        return view('user.dashboard');
    }
}
