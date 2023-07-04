<?php

use App\Models\Role;
use Illuminate\Support\Facades\Auth;

  $user = Auth::user();
?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/apple-icon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/setting-logo') }}">
  <title>
    {{  env('APP_NAME') }}
  </title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="{{ asset('assets/css/nucleo-icons.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="{{ asset('assets/css/nucleo-svg.css') }}" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="{{ asset('assets/css/argon-dashboard.css') }}" rel="stylesheet" />
</head>

<body class="error-page">
  <main class="main-content  mt-0">
    <div class="page-header min-vh-100" style="background-image: url('<?php echo asset('assets/img/illustrations/404.svg')?>');">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6 col-md-7 mx-auto text-center">
            <h1 class="display-1 text-bolder text-primary">Error 404</h1>
            <h2>Product Not found</h2>
            <p class="lead">Maybe the product doesn't belongs you. We suggest you to go to the dashboard.</p>
            <?php

              $adminRole = Role::where('slug', 'admin')->first();
              $userRoles = $user->roles;

              if ($userRoles->contains($adminRole)) {
              ?>
                <button type="button" class="btn bg-gradient-dark mt-4" onclick="window.location.href='<?php echo route('admin.dashboard')?>'">Go to Dashboard</button>
              <?php
              }else{ ?>
                <button type="button" class="btn bg-gradient-dark mt-4" onclick="window.location.href='<?php echo route('user.dashboard')?>'">Go to Dashboard</button>
              <?php } ?>

          </div>
        </div>
      </div>
    </div>
  </main>
</body>
</html>