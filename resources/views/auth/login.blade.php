@extends('layouts.auth')
@section('content')
    @include('navbar.auth-nav')
    <!-- End Navbar -->
    <main class="main-content  mt-0">
        <div class="page-header align-items-start min-vh-50 pt-7 pb-9 m-3 border-radius-lg"
            style="background-image: url('https://images.pexels.com/photos/1619569/pexels-photo-1619569.jpeg');">
            <span class="mask bg-gradient-dark opacity-6"></span>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12 text-center mx-auto">
                        <h1 class="text-white mb-2 mt-5">Released Inmates Tablets Unlock Reporting Log</h1>
                        <p class="text-lead text-white"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row mt-lg-n10 mt-md-n11 mt-n10 justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
                    <div class="card mt-5">
                        <div class="card-header pb-0 text-start">
                            <h3 class="font-weight-bolder">Welcome</h3>
                            <p class="mb-0">Enter your email and password to sign in</p>
                        </div>
                        <div class="card-body">
                            <form id="form" method="POST" action="{{ route('user.login') }}">
                                @csrf
                                <label>Email</label>
                                <div class="mb-3">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Email" name="email" id="email" aria-label="Email"
                                        value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <label>Password</label>
                                <div class="mb-3">
                                    <input type="password" class="form-control" placeholder="Password" name="password"
                                        id="password" aria-label="Password" required>
                                    <div class="invalid-feedback">
                                        <span><i class="fi fi-rs-info"></i></span>
                                        Password is required
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button id="submit" type="submit" class="btn btn-primary w-100 mt-4 mb-0">Sign
                                        in</button>
                                </div>
                            </form>
                        </div>
                        {{-- <div class="card-footer text-center pt-0 px-lg-0 px-0">
                            <p class="mb-4 text-sm mx-auto">
                                Forget Your Password?
                                <a href="#" class="text-primary font-weight-bold">Click Here</a>
                            </p>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- -------- START FOOTER 3 w/ COMPANY DESCRIPTION WITH LINKS & SOCIAL ICONS & COPYRIGHT ------- -->
@endsection
