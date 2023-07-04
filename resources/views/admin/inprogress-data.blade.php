@extends('layouts.admin')
@section('content')
    <style>
        .inmate {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 15ch;
        }
    </style>
    <div class="row h-80vh">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header pb-0">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">In Progress Entries</h5>
                                <p class="text-sm mb-0">
                                    You can create edit and delete a entry.
                                </p>
                            </div>

                            <div class="ms-auto my-auto mt-lg-0 mt-4">
                                <div class="ms-auto my-auto">
                                    <a href="{{ route('admin.create.data') }}"
                                        class="btn bg-gradient-primary btn-sm mb-0">+&nbsp; Add New Entry</a>
                                    <a href="{{ route('admin.export.data') }}"
                                        class="btn btn-outline-primary btn-sm mb-0 mt-sm-0 mt-1"
                                        {{-- onclick="ExportToExcel('xlsx')">Export</button> --}}>Export</a>
                                    <button class="btn btn-outline-success btn-sm mb-0 mt-sm-0 mt-1" data-bs-toggle="modal"
                                        data-bs-target="#import-modal">Import</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- File Modal -->
                    <div class="modal fade" id="import-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Import File</h5>
                                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal"
                                        aria-label="Close"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="modal-body">
                                    <p><a href="{{ asset('assets/Entries Demo.xlsx') }}"> <i class="fas fa-download"></i>
                                            Download the demo xlsx file</a></p>
                                    <form id="import-form" class="form-control" multipart>
                                        <div class="form-group">
                                            <label for="note">File:</label>
                                            <input type="file" class="form-control" id="excel_file" name="excel_file"
                                                rows="3" accept=".xlsx,.xls">
                                            <div class="text-danger" id="excel-file-error"></div>
                                        </div>
                                        <div class="d-flex justify-content-end mt-4">
                                            <button type="submit" id="submit_btn" name="button"
                                                class="btn bg-gradient-primary m-0 ms-2">Upload</button>
                                            <button id="loader_btn" class="btn bg-gradient-primary m-0 ms-2" type="button"
                                                disabled hidden>
                                                <span class="spinner-border spinner-border-sm" role="status"
                                                    aria-hidden="true"></span>
                                                Loading...
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card-header pb-0 col-md-4">
                        <div>
                            <h6 class="mb-0">Filter</h6>
                        </div>
                        <form id="filter" method="get" action="{{ route('admin.filter.data') }}" class="form-inline">
                            @csrf
                            <div class="row">
                                <div class="form-group">
                                    <label for="state">Type</label>
                                    <select class="form-control" id="type" name="type">
                                        <option value="date" selected>By Date</option>
                                        <option value="month">By Month</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="date-filter">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="end-date"> Start Date:</label>
                                        <input type="date" id="start-date" name="start_date" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="end-date"> End Date:</label>
                                        <input type="date" id="end-date" name="end_date" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="month-filter">
                                <div class="form-group">
                                    <label for="state">Month</label>
                                    <select class="form-control" name="month" id="month">
                                        <option value="1" selected>January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                            <button type="button" id="reset-filter" class="btn btn-outline-warning btn-sm"
                                onclick="window.location.reload()" hidden>Reset</button>
                            <button class="btn btn-outline-primary btn-sm" id="export-filter" hidden
                                onclick="ExportToExcel('xlsx')">Export</button>
                        </form>
                    </div>

                    <div class="card-body px-0 pb-0">
                        <div class="ms-auto my-auto mt-lg-0 mt-4 p-3">
                            <div class="ms-auto my-auto">
                                <a href="{{ route('admin.data') }}" class="btn btn-outline-primary btn-sm mb-0 mt-2">All Entries</a>
                                <a href="{{ route('admin.inprogress.data') }}" class="btn bg-gradient-primary btn-sm mb-0 mt-2">In Progress</a>
                                <a href="{{ route('admin.complete.data') }}" class="btn btn-outline-primary btn-sm mb-0 mt-2">Completed</a>
                                <a href="{{ route('admin.duplicate.data') }}" class="btn btn-outline-primary btn-sm mb-0 mt-2">Duplicate Entries</a>

                            </div>
                        </div>
                        <div class="tab-content px-3" id="pills-tabContent">
                            {{-- All Entry Tab --}}
                            {{-- <div class="tab-pane fade show active" id="all-entries-tabs" role="tabpanel"
                                aria-labelledby="pills-profile-tab"> --}}

                                <div class="table-responsive">
                                    <div id="table_body">
                                        <table class="table table-flush" id="inprogress-data-list">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th class="text-sm">Name</th>
                                                    <th class="text-sm">Inmate ID</th>
                                                    <th class="text-sm">Request For</th>
                                                    <th class="text-sm">Tracking Number</th>
                                                    <th>Attachment</th>
                                                    <th class="text-sm">Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>

                            {{-- </div> --}}
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="view-modal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="view-modal-title">Entry</h5>
                                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal"
                                        aria-label="Close"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="modal-body" id="view-modal-body">

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <!-- Magnific Popup core JS file -->
    <!-- datatable -->
    <script>
        $(document).ready(function() {

            $('#inprogress-data-list').DataTable({
                pageLength: 50,
                processing: true,
                serverSide: true,
                ajax: "{{ route('admin.inprogress.data') }}",
                columns: [
                    {
                        data: 'name',
                        name: 'name',
                        className: 'text-sm'
                    },
                    {
                        data: 'unique_id',
                        name: 'unique_id',
                        className: 'text-sm'
                    },
                    {
                        data: 'requested_device',
                        name: 'requested_device',
                        className: 'text-sm'
                    },
                    {
                        data: 'traking_number',
                        name: 'traking_number',
                        className: 'text-sm',
                    },
                    {
                        data: 'attachment',
                        name: 'attachment',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-sm',
                    },
                ]
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#month-filter').hide();
            $('#type').on('change', function() {
                if ($(this).val() == 'month') {
                    $('#month-filter').show();
                    $('#date-filter').hide();
                } else {
                    $('#month-filter').hide();
                    $('#date-filter').show();
                }
            });
        });


        if (document.getElementById('editor')) {
            var quill = new Quill('#editor', {
                theme: 'snow' // Specify theme in configuration
            });
        }

        function delete_data(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f0934A',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(function() {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: "{{ route('admin.delete.data') }}",
                            type: "DELETE",
                            data: {
                                id: id,
                            },
                            dataType: "json",
                            success: function(data) {
                                Swal.fire(
                                    'Deleted!',
                                    'The entry has been successfully deleted.',
                                    'success'
                                )
                                setTimeout(() => window.location.reload(), 1000);

                            },
                        });
                    });
                }
            });
        }

        function view_data(id) {
            $(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('admin.view.data') }}",
                    type: "GET",
                    data: {
                        id: id,
                    },
                    dataType: "json",
                    success: function(data) {
                        $("#view-modal-body").html(data["data"]);
                        $("#view-modal-title").html(data["name"]);
                    },
                });
            });
        }

        $("#import-form").on('submit', function(e) {
            $("#submit_btn").attr('hidden', true);
            $("#loader_btn").removeAttr('hidden');
            $("#excel-file-error").html();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var data = new FormData(document.getElementById('import-form'));
            e.preventDefault();
            $.ajax({
                data: data,
                url: "{{ route('upload.excel') }}",
                type: "POST",
                // dataType: 'json',
                processData: false,
                contentType: false,
                success: function(data) {
                    $("#loader_btn").attr('hidden', true);
                    $("#submit_btn").removeAttr('hidden');

                    data = JSON.parse(data);

                    if (data.error == 1) {
                        $('#excel-file-error').html(data.message);
                    } else {
                        Swal.fire({
                            icon: 'success',
                            title: 'Successful',
                            text: 'Entries Successfully Imported',
                        });
                        setTimeout(() => window.location.href = "{{ route('admin.data') }}", 2000);
                    }


                },
                error: function(data) {
                    $("#loader_btn").attr('hidden', true);
                    $("#submit_btn").removeAttr('hidden');
                    if (data.responseJSON.errors.excel_file[0]) {
                        $("#excel-file-error").html(data.responseJSON.errors.excel_file[0]);
                    }

                }
            });
        });
    </script>
@endsection
