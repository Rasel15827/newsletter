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
    @php
        $type = request('type');
        $start_date = request('start_date');
        $end_date = request('end_date');
        $month = request('month');
    @endphp
    <div class="row h-80vh">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <!-- Card header -->
                    <div class="card-header pb-0">
                        <div class="d-lg-flex">
                            <div>
                                <h5 class="mb-0">Filtered Entries</h5>
                            </div>

                            {{-- <div class="ms-auto my-auto mt-lg-0 mt-4">
                                <div class="ms-auto my-auto">
                                    <a href="{{ route('admin.create.data') }}"
                                        class="btn bg-gradient-primary btn-sm mb-0">+&nbsp; Add New Entry</a>
                                    <a href="{{ route('admin.export.data') }}"
                                        class="btn btn-outline-primary btn-sm mb-0 mt-sm-0 mt-1"
                                        onclick="ExportToExcel('xlsx')">Export</button>Export</a>
                                </div>
                            </div> --}}
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
                                        <option value="date" @php
echo $type == 'date' ? 'selected' : ''; @endphp>
                                            By Date</option>
                                        <option value="month" @php
echo $type == 'month' ? 'selected' : ''; @endphp>By
                                            Month</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row" id="date-filter" @php
echo $type == 'date' ? '' : 'hidden'; @endphp>
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="end-date"> Start Date:</label>
                                        <input type="date" id="start-date" name="start_date" value="{{ $start_date }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="end-date"> End Date:</label>
                                        <input type="date" id="end-date" name="end_date" value="{{ $end_date }}"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="month-filter" @php
echo $type == 'month' ? '' : 'hidden'; @endphp>
                                <div class="form-group">
                                    <label for="state">Month</label>
                                    <select class="form-control" name="month" id="month">
                                        <option value="1" @php
echo $month == '1' ? 'selected' : ''; @endphp>January
                                        </option>

                                        <option value="2" @php
echo $month == '2' ? 'selected' : ''; @endphp>February
                                        </option>

                                        <option value="3" @php
echo $month == '3' ? 'selected' : ''; @endphp>March
                                        </option>

                                        <option value="4" @php
echo $month == '4' ? 'selected' : ''; @endphp>April
                                        </option>

                                        <option value="5" @php
echo $month == '5' ? 'selected' : ''; @endphp>May
                                        </option>

                                        <option value="6" @php
echo $month == '6' ? 'selected' : ''; @endphp>June
                                        </option>

                                        <option value="7" @php
echo $month == '7' ? 'selected' : ''; @endphp>July
                                        </option>

                                        <option value="8" @php
echo $month == '8' ? 'selected' : ''; @endphp>August
                                        </option>

                                        <option value="9" @php
echo $month == '9' ? 'selected' : ''; @endphp>September
                                        </option>

                                        <option value="10" @php
echo $month == '10' ? 'selected' : ''; @endphp>October
                                        </option>

                                        <option value="11" @php
echo $month == '11' ? 'selected' : ''; @endphp>November
                                        </option>

                                        <option value="12" @php
echo $month == '12' ? 'selected' : ''; @endphp>December
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm">Filter</button>

                        </form>
                    </div>
                    <div class="card-header pb-0">
                        <div class="d-lg-flex">
                            <div class="ms-auto my-auto mt-lg-0">
                                <div class="ms-auto my-auto">
                                    <form class="pr-2" id="filter-export-form"
                                        action="{{ route('admin.conditional.export.data') }}" method="get">
                                        <input name="start_date" value="{{ $start_date }}" hidden>
                                        <input name="end_date" value="{{ $end_date }}" hidden>
                                        <input name="type" value="{{ $type }}" hidden>
                                        <input name="month" value="{{ $month }}" hidden>
                                        <a id="reset-filter" class="btn btn-outline-warning btn-sm"
                                            href="{{ route('admin.data') }}">Reset</a>
                                        <button class="btn btn-outline-primary btn-sm" type="submit">Export</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body px-0 pb-0">
                        <div class="table-responsive px-3">
                            <div id="table_body">
                                <table class="table table-flush" id="data-list">
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
    <!-- Magnific Popup core JS file -->
    <!-- datatable -->
    <script>
        $(document).ready(function() {

            $('#data-list').DataTable({
                pageLength: 50,
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.filter.data') }}",
                    type: "GET",
                    data: function(d) {
                        d.start_date = $('#start-date').val();
                        d.end_date = $('#end-date').val();
                        d.month = $('#month').val();
                        d.type = $('#type').val();
                    }
                },
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
            $('#type').on('change', function() {
                if ($(this).val() == 'month') {
                    $('#month-filter').removeAttr('hidden', true);
                    $('#date-filter').prop('hidden', true);
                } else {
                    $('#month-filter').prop('hidden', true);
                    $('#date-filter').removeAttr('hidden', true);
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
    </script>
@endsection
