@extends('layouts.admin')
@section('content')
    <div class="h-80vh">
      <div class="row">
        <div class="col-lg-12">
          <div class="row">
            <div class="col-lg-3 col-md-6 col-12">
              <div class="card  mb-4">
                <div class="card-body p-3">
                  <div class="row">
                    <div class="col-8">
                      <div class="numbers">
                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Users</p>
                        <h5 class="font-weight-bolder">
                          {{ $total_users }}
                        </h5>
                        {{-- <p class="mb-0">
                          <span class="text-danger text-sm font-weight-bolder">-2%</span>
                          since last quarter
                        </p> --}}
                      </div>
                    </div>
                    <div class="col-4 text-end">
                      <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                        <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3 col-md-6 col-12">
              <div class="card  mb-4">
                <div class="card-body p-3">
                  <div class="row">
                    <div class="col-8">
                      <div class="numbers">
                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Total Entries</p>
                        <h5 class="font-weight-bolder">
                          {{ $total_entries }}
                        </h5>
                        {{-- <p class="mb-0">
                          <span class="text-danger text-sm font-weight-bolder">-2%</span>
                          since last quarter
                        </p> --}}
                      </div>
                    </div>
                    <div class="col-4 text-end">
                      <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                        <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>


@endsection

 