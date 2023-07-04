<?php

use Illuminate\Support\Facades\Route;

$routeName = Route::currentRouteName();
?>
<div class="min-height-300 bg-primary position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="{{route('admin.dashboard')}}">
        <img src="/assets/img/setting-logo.png" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">{{ config('app.name') }}</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto h-auto" id="sidenav-collapse-main">
      <ul class="navbar-nav">

        <li class="nav-item ">      
          <a class="nav-link @if ($routeName == 'admin.dashboard')
            active
          @endif" href="{{ route('admin.dashboard') }}">
            <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                <i class="ni ni-shop text-primary text-sm opacity-10"></i>
            </div> 
            <span class="nav-link-text ms-1"> Dashboard</span>
          </a>
        </li>

        <li class="nav-item ">      
          <a class="nav-link @if ($routeName == 'admin.users') active @endif" href="{{ route('admin.users') }}">
            <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                <i class="ni ni-circle-08 text-primary text-sm opacity-10"></i>
                
              </div> 
            <span class="nav-link-text ms-1">Users</span>
          </a>
        </li>

        <li class="nav-item ">      
          <a class="nav-link @if ($routeName == 'admin.data') active @endif" href="{{ route('admin.data') }}">
            <div class="icon icon-shape icon-sm text-center d-flex align-items-center justify-content-center">
                <i class="ni ni-briefcase-24 text-primary text-sm opacity-10"></i>
                
              </div> 
            <span class="nav-link-text ms-1">All Entries</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>